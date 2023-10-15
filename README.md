# Livewire HMVC (Beta)

[![Latest Stable Version](https://poser.pugx.org/hexters/wirehmvc/v/stable)](https://packagist.org/packages/hexters/wirehmvc)
[![Total Downloads](https://poser.pugx.org/hexters/wirehmvc/downloads)](https://packagist.org/packages/hexters/wirehmvc)
[![License](https://poser.pugx.org/hexters/wirehmvc/license)](https://packagist.org/packages/hexters/wirehmvc)

This package is a support package for the [hexters/wirehmvc](https://github.com/hexters/wirehmvc) package specifically made to integrate [hexters/wirehmvc](https://github.com/hexters/wirehmvc) with livewire version 3.

To install through Composer, by run the following command:

```bash
composer require hexters/wirehmvc
```

Follow the command below to create a module with livewire support.
```bash
php artisan module:make Blog --command=module:livewire-init
```
You can also do this with an existing module, but remember that. The `route.php` file will be replaced by a new file.
```bash
php artisan module:livewire-init --module=Blog
```


## Artisan 

```bash
php artisan module:make-livewire
php artisan module:livewire-attribute
php artisan module:livewire-form
php artisan module:livewire-delete
```
## Rendering components

Rendering components can only be done on components in the module folder, or you can see `Modules\Blog\Http\Middleware\LivewireSetupBlogMiddleware` class. I assume the module name is `Blog`!

## Layout Customization

You can customize the bottom layout of livewire by running the following command.
```bash
php artisan module:livewire-layout --module=Blog
```

The system will create component files, and middleware. To change the layout configuration in livewire, you must attach the middleware to the RouteServiceProvider file in the module, I assume for the `Modules\Blog\Providers\RouteServiceProvider` class see the example below.
```php
. . .

use Modules\Blog\Http\Middleware\LayoutBlogMiddlware;

. . . 
 
Route::middleware([
    'web',
    ModuleBlogStatusMiddleware::class,
    LivewireSetupBlogMiddleware::class,
    LayoutBlogMiddlware::class,
])->namespace($this->namespace)

. . . 

```

