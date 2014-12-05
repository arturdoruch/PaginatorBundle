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

##Controller

Currently paginator can paginate:

* Doctrine\ORM\Query
* Doctrine\ORM\QueryBuilder

```php
// Acme\ProjectBundle\Controller\ProjectController.php

public function listAction($page, Request $request)
{
    $repository = $this->getDoctrine()->getRepository('AcmeProjectBundle:Project');
    $qb = $repository->createQueryBuilder('p')
            ->select('p');
            
    $paginator = $this->get('arturdoruch_paginator');
    $projects = $paginator->paginate($qb, $page, 5 /*limit per page*/);

    return $this->render('AcmeProjectBundle:Project:list.html.twig', array(
        'projects' => $projects
    ));
}
```

##View

In twig template you can use several functions to display all paginate list data.
Each of them require Pagination class instance as parameter. See example below.

```twig
{# Pagination #}
{{ arturdoruch_pagination(projects.pagination) }}

{# Displayed items range #}
{{ arturdoruch_pagination_displayed_items(projects.pagination) }}

{# Total items count #}
{{ arturdoruch_pagination_total_items(projects.pagination) }}

{# Renders all pagination parts: pagination, items range, total items #}
{{ arturdoruch_pagination_all(projects.pagination) }}

<table>
    <thead>
        <tr>
            <td>Id</td>
            <td>Name</td>
        </tr>
    </thead>
    <tbody>
    {% for project in projects %}
        <tr>
            <td>{{ project.id }}</td>
            <td>{{ project.name }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>
```
