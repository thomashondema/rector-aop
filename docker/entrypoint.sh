#!/usr/bin/env sh

SRC="/app_src"
DST="/app"
SOURCE_CODE="${SOURCE_DIR:-src}"

link_entry() {
  src="$1"
  rel="${src#"$SRC"/}"
  dst="$DST/$rel"

  mkdir -p "$(dirname "$dst")"
  if [ "$src" != "$dst" ]; then
    ln -sfn "$src" "$dst"
  fi
}

copy_entry() {
  src="$1"
  rel="${src#"$SRC"/}"
  dst="$DST/$rel"

  mkdir -p "$(dirname "$dst")"

  if [ -d "$src" ]; then
    rsync -a --delete "$src/" "$dst/"
  else
    rsync -a "$src" "$dst"
  fi
}

run_rector_if_file() {
  src="$1"
  rel="${src#"$SRC"/}"
  dst="$DST/$rel"

  if [ -f "$dst" ]; then
    cd "$DST" && php vendor/bin/rector process "$dst" --config=rector-build.php
  fi
}

sync_all() {
  rm -rf "${DST:?}"/*

  find "$SRC" -mindepth 1 -maxdepth 1 | while read -r entry; do
    name="$(basename "$entry")"

    if [ "$name" = "$SOURCE_CODE" ]; then
      copy_entry "$entry"
    else
      link_entry "$entry"
    fi
  done
}

sync_one() {
  changed="$1"
  rel="${changed#"$SRC"/}"
  top="${rel%%/*}"

  # If the change is inside the source code directory, copy it
  case "$rel" in
    "$SOURCE_CODE"/*|"$SOURCE_CODE")
      if [ -e "$changed" ]; then
        copy_entry "$changed"
        run_rector_if_file "$changed"
      else
        rm -rf "${DST:?}/$rel"
      fi
      return
      ;;
  esac

  # Otherwise just keep a symlink in place
  if [ -e "$changed" ]; then
    link_entry "$changed"
  else
    rm -f  "${DST:?}/$rel"
  fi
}

echo "Initial sync..."
sync_all

echo "Watching for changes..."
inotifywait -mr -e close_write,create,delete,move --format '%w%f' "$SRC" | \
while read -r file; do
  sync_one "$file"
done
