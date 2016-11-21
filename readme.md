## Laraboard

Laraboard attempts to provide an easy to use, feature rich, Laravel powered forum package.

It is currently under heavy development and not recommended for production environments.

## Installation

### Composer

Require this package with composer:

```
composer require christhompsontldr/laraboard
```

### Service Provider

After updating composer, add the ServiceProvider to the providers array in config/app.php

#### Laravel 5.x:

```
Christhompsontldr\Laraboard\ServiceProvider::class,
```

### Config

Now move the config files from the package into your application

```
php artisan vendor:publish
```

This will create `config/laraboard.php` and `config/laratrust.php`.  If you want to modify table prefixes or other information, now is the time to do it.

### Setup

The next command will create migrations, create the `Role` and `Permission` models and add traits to your application's User model.

```
php artisan laraboard:setup
```


#### Laratrust Already Installed?

If you already have [Laratrust](https://github.com/santigarcor/laratrust) installed, you have the option to not set it up now

```
php artisan laraboard:setup --no-laratrust
```

#### Want to create migrations before running setup?

This will allow you to create the migrations only.  You can then modify them.  Run this before the `setup` command.

```
php artisan laraboard:migrations
```

### Migrate

Run the migrations

```
php artisan migrate
```

###  Role

If you have not created the role found in the `laraboard.user.admin_role`, create it now and associate it with a user.


### Design

Laravel blade stacks are utilized to include required JS and CSS.

Please include this in the `<head>` of your main layout:

```
@stack('styles')
```

and this above `</body>`

```
@stack('scripts')
```

## Dependencies

### Auth
Laraboard utilizes Laravel's built in [Authentication](https://laravel.com/docs/5.3/authentication) and [Authorization](https://laravel.com/docs/5.3/authorization) systems.

### CSS/DOM
Laraboard uses [Bootstrap](https://getbootstrap.com/) for styling and DOM structure.

### HTML & Forms
The [Laravel Collective](https://laravelcollective.com/) package is utilizes for building HTML and forms.  If you aren't already using it, no worries, Laraboard will install it.