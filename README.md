PaginatorBundle
===============

Simple paginator for Symfony2. This paginator uses Doctrine ORM query builder.

# Installation

Add the following line to your composer.json require block
```sh
"arturdoruch/paginator-bundle": "dev-master"
```
and this into repositories key. If "repositories" key doesn't exists create them.
```sh
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/arturdoruch/PaginatorBundle"
  }
],
```

Execute command in composer
```sh
php composer.phar update arturdoruch/paginator-bundle
```

Add PaginatorBundle to your application kernel
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new ArturDoruch\PaginatorBundle\ArturDoruchPaginatorBundle(),
        // ...
    );
}
```

# Usage
