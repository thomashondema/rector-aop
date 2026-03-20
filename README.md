# AOP in PHP with Rector
This package demonstrates how to use Rector as an AOP weaver in PHP. It provides a set of base classes and 
utilities that make it easier to create AOP-like transformations in your codebase. With Rector AOP, you can 
implement cross-cutting concerns such as logging, caching, security, and database transactions without manually 
modifying your code.

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

use Rector\Config\RectorConfig;
use RectorRules\Laravel\AddDatabaseTransactions;

return RectorConfig::configure()
    ->withImportNames()
    ->withPaths([
        __DIR__ . '/app/Services/',
    ])
    ->withRules([
        AddDatabaseTransactions::class,
    ]);

```

Run Rector with your custom configuration:
```bash
vendor/bin/rector --config=rector-build.php
```

# Docker
You can also run Rector-AOP as a Docker service. Add the rector-aop service definition and the app_build volume to your `docker-compose.yml`:

```yaml
services:
    app:
        volumes:
            - app_build:/app
        working_dir: '/app/'
        depends_on:
            - rector-aop
    rector-aop:
        build:
            context: ./
            dockerfile: vendor/thomashondema/rector-aop/docker/Dockerfile
        command: [ "sh", "-c", "/app_src/vendor/thomashondema/rector-aop/docker/entrypoint.sh" ]
        volumes:
            - ./:/app_src:ro
            - app_build:/app
volumes:
    app_build:
```
This configuration sets up a `rector-aop` service that applies Rector AOP transformations to your code. The `app` service depends on `rector-aop`, ensuring that the transformations are applied before your application runs. The source code is mounted as a read-only volume to prevent accidental modifications, while the transformed code is stored in a separate volume for use by the `app` service.
This ensures that during development, you are running the same code as in production, and you can easily test your AOP transformations without modifying your local codebase.
