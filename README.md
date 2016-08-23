PaginatorBundle
===============

Simple paginator for Symfony2, which can paginate:
* array
* Doctrine\ORM\Query
* Doctrine\ORM\QueryBuilder
* Doctrine\ODM\MongoDB\Query\Builder
* Doctrine\ODM\MongoDB\Query\Query
* Doctrine\MongoDB\CursorInterface
* MongoCursor

## Installation

Add the following line to your composer.json require block
```json
"require": {
    ...
    "arturdoruch/paginator-bundle": "~1.0"
}
```

and run composer command
```sh
composer update arturdoruch/paginator-bundle
```

or simply
```sh
composer require arturdoruch/paginator-bundle
```

Register ArturDoruchPaginatorBundle in your application kernel class
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new ArturDoruch\PaginatorBundle\ArturDoruchPaginatorBundle(),
    );
}
```

## Configuration

```yml
// app/config/config.yml

artur_doruch_paginator:
    limit: 10                        # Default value of displayed items per page
    prev_page_label: '&#8592; Prev'  # Pagination previous page button label
    next_page_label: 'Next &#8594;'  # Pagination next page button label
```

## Usage

### Controller

Get paginator in controller method.
```php
$paginator = $this->get('arturdoruch_paginator');
```

Paginate items list.
```php
$paginator->paginate($query, $page, $limit);
```

<a name="#paginate-parameter"></a>
ArturDoruch\PaginatorBundle\Paginator::paginate() method receive three parameters:
* $query (mixed) A Doctrine ORM query or query builder, Doctrine mongodb ODM query or query builder,
instance of Doctrine\MongoDB\CursorInterface, instance of MongoCursor, or array with arrays of items.
* $page (integer) Number of page to display
* $limit (integer) The number of items per page. Possible values are:
    * -1 - fetch all items (limit will be omitted)
    * 0 - default limit (setting in config "artur_doruch_paginator.limit") will be used
    * integer positive - given $limit value will be used

#### Examples

Paginate items with Doctrine ORM query and query builder.
```php
// Acme\ProjectBundle\Controller\ProjectController.php

public function listAction($page, Request $request)
{
    $repository = $this->getDoctrine()->getRepository('AcmeProjectBundle:Project');
    $paginator = $this->get('arturdoruch_paginator');
    
    // Doctrine\ORM\QueryBuilder
    $qb = $repository->createQueryBuilder('p')
        ->select('p');
    
    $projects = $paginator->paginate($qb, $page, 5);

    // Doctrine\ORM\Query
    $query = $repository->createQueryBuilder('p')
        ->select('p')
        ->getQuery();

    $projects = $paginator->paginate($query, $page, 5);    

    return $this->render('AcmeProjectBundle:Project:list.html.twig', array(
        'projects' => $projects
    ));
}
```

Paginate items with Doctrine ODM MongoDB query and query builder.
```php
// todo
```

Paginate items with Doctrine\MongoDB\CursorInterface and MongoCursor.
```php
// todo
```

Paginate items from array. Array can contain array or object collection.

```php
// Acme\ProjectBundle\Controller\ProjectController.php

public function listAction($page, Request $request)
{
    $projectsList = array(
            array(
                'id' => 1,
                'name' => 'PHP'
            ),
            array(
                'id' => 2,
                'name' => 'JS'
            ),
            array(
                'id' => 3,
                'name' => 'Symfony'
            ),
            array(
                'id' => 4,
                'name' => 'Github'
            ),
            array(
                'id' => 5,
                'name' => 'SCSS'
            )
            ...
        );

    $paginator = $this->get('arturdoruch_paginator');
    $projects = $paginator->paginate($projectsList, $page, 5);

    return $this->render('AcmeProjectBundle:Project:list.html.twig', array(
        'projects' => $projects
    ));
}
```

### View

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
