# View Models

[![Packagist PHP support](https://img.shields.io/packagist/php-v/sfneal/view-models)](https://packagist.org/packages/sfneal/view-models)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sfneal/view-models.svg?style=flat-square)](https://packagist.org/packages/sfneal/view-models)
[![Build Status](https://travis-ci.com/sfneal/view-models.svg?branch=master&style=flat-square)](https://travis-ci.com/sfneal/view-models)
[![StyleCI](https://github.styleci.io/repos/288782334/shield?branch=master)](https://github.styleci.io/repos/288782334?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sfneal/view-models/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sfneal/view-models/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/sfneal/view-models.svg?style=flat-square)](https://packagist.org/packages/sfneal/view-models)

spatie/view-models wrapper with built in response caching

## Installation

You can install the package via composer:

```bash
composer require sfneal/view-models
```

## Usage
A view model is a class where you can put some complex logic for your views. This will make your controllers a bit
lighter. You can create a view model by extending the provided Sfneal\ViewModels\AbstractViewModel.

```php
use Sfneal\ViewModels\ViewModel;

class PostViewModel extends ViewModel
{
    public $indexUrl = null;
   

    public function __construct(User $user, Post $post = null)
    {
        $this->user = $user;
        $this->post = $post;
        
        $this->indexUrl = action([PostsController::class, 'index']);
        $this->view = 'your.view'; 
    }
    
    public function post(): Post
    {
        return $this->post ?? new Post();
    }
    
    public function categories(): Collection
    {
        return Category::canBeUsedBy($this->user)->get();
    }
}
```

And used in controllers like so:
```php
class PostsController
{
    public function create()
    {
        // Uses caching for fast load times after first request
        return (new PostViewModel(current_user()))->render();
    }
    
    public function edit(Post $post)
    {
        // Doesn't use caching to avoid need for cache invalidation on changes
        return (new PostViewModel(current_user(), $post))->renderNoCache();
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email stephen.neal14@gmail.com instead of using the issue tracker.

## Credits

- [Stephen Neal](https://github.com/sfneal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
