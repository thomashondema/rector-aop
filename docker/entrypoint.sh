sync_all() {
  rm -rf /app/*
  rsync -a \
    --exclude .git \
    --exclude .idea \
    --exclude .vscode \
    --exclude .build \
    /app_src/ /app/
  cd /app && php vendor/bin/rector process --config=rector-build.php
}

sync_one() {
  changed="$1"
  rel="${changed#/app_src/}"
  dst="/app/$rel"

  if [ -e "$changed" ]; then
    mkdir -p "$(dirname "$dst")"
    rsync -a "$changed" "$dst"
    cd /app && php /app/vendor/bin/rector process "$dst" --config=rector-build.php
  else
    rm -f "$dst"
  fi
}

echo "Initial sync..."
sync_all

echo "Watching for changes..."
inotifywait -mr -e close_write,create,delete,move --format '%w%f' /app_src | \
while read file; do
  sync_one "$file"
done
