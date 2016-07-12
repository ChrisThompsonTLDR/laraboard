## Laraboard

Laraboard attempts to provide an easy to use, feature rich, Laravel powered forum package.

It is currently under heavy development and not recommended for production environments.

## Installation

Require this package with composer:

```
composer require christhompsontldr/laraboard
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

### Laravel 5.x:

```
Christhompsontldr\Laraboard\ServiceProvider::class,
```

The migrations tag is the only required tag.

Copy the package migrations to your local config with the publish command:

```
php artisan vendor:publish --provider="Christhompsontldr\Laraboard\ServiceProvider" --tag=migrations
```

Run the migration files

```
php artisan migrate
```

The other tags that are available are

 - views - if you want to overwrite the views
 - config - allowing you to config the forums
 - seeds - for seeding test data
