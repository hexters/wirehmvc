# Livewire HMVC (Beta)

[![Latest Stable Version](https://poser.pugx.org/hexters/wirehmvc/v/stable)](https://packagist.org/packages/hexters/wirehmvc)
[![Total Downloads](https://poser.pugx.org/hexters/wirehmvc/downloads)](https://packagist.org/packages/hexters/wirehmvc)
[![License](https://poser.pugx.org/hexters/wirehmvc/license)](https://packagist.org/packages/hexters/wirehmvc)

This package is a support package for the [hexters/laramodule](https://github.com/hexters/laramodule) package specifically made to integrate [hexters/laramodule](https://github.com/hexters/laramodule) with livewire version 3.

To install through Composer, by run the following command:

```bash
composer require hexters/wirehmvc
```
## Autoloading
By default the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example :
```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/",
      "Database\\Factories\\": "database/factories/",
      "Database\\Seeders\\": "database/seeders/"
    }
  }
}
```
And make `Modules` directory in your root project folder

```bash
mkdir Modules
```

Don't forget to run the commands below

```bash
composer dump-autoload
```
## Create Module

Follow the command below to create a module, and select Livewire in preset option!
```bash
php artisan module:make Blog
```
You can also do this with an existing module, but remember that. The `route.php` file will be replaced by a new file.
```bash
php artisan module:livewire-init --module=Blog
```


## Artisan 

```bash
php artisan module:make-livewire Counter --module=Blog
php artisan module:livewire-attribute ArticleTileAttribute --module=Blog
php artisan module:livewire-form ArticleForm --module=Blog
php artisan module:livewire-layout --name=app --module=Blog
php artisan module:livewire-delete Counter --module=Blog
```

More complete commands can be seen at the link below.
### [Artisan Documentation](https://github.com/hexters/laramodule#artisan)


## Rendering components

Rendering components can only be done on components in the module folder, or you can see `Modules\Blog\Http\Middleware\LivewireSetupBlogMiddleware` class. I assume the module name is `Blog`!
