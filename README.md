
## Quick Start Guide for Rector AOP

Install the Rector AOP package, only required if you want to extend from the existing rules provided by the package, 
you can create your own rules without this package. However, if you do not install this package, make sure 
rector/rector is available at build time.

```bash
composer require thomashondema/rector-aop
```

Create a directory for Rector rules:
`mkdir rector-rules`

Add PSR-4 autoloading for Rector rules in `composer.json`:

```JSON
"autoload": { 
    "psr-4": {
        "App\\": "app/",
        "RectorRules\\": "rector-rules/"
    }
},
```
Create a Rector configuration file `rector-build.php` in the root of your project:

```PHP
<?php
declare(strict_types=1);
```

Run Rector with our custom configuration:
```bash
vendor/bin/rector --config=rector-build.php
```
