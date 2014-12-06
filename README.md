PaginatorBundle
===============

Simple paginator for Symfony2. Working with paginator Doctrine ORM Query and array.

## Installation

Add the following line to your composer.json require block
```json
"require": {
    ...
    "arturdoruch/paginator-bundle": "dev-master"
}
```

Install bundle by running this command in terminal.
```sh
php composer.phar update arturdoruch/paginator-bundle
```

Add ArturDoruchPaginatorBundle to your application kernel
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

## Configuration

ArturDoruchPaginatorBundle currently provides only one optional parameter "limit".
This is default limit value for paginator, used when paginate parameter $limit will be set null (see Controller section).
If you skip this configuration, default limit will be set 10.

```yml
// app/config/config.yml

artur_doruch_paginator:
    limit: 20
```

## Usage

### Controller

Paginator can paginate:

* Array
* Doctrine\ORM\Query
* Doctrine\ORM\QueryBuilder

Get paginator in controller method.
```php
$paginator = $this->get('arturdoruch_paginator');
```

Paginate items list.
```php
$paginator->paginate($query, $page, $limit);
```

<a name="#paginate-parameter"></a>
Paginate method receive three parameters:
* $query - Doctrine ORM query or Doctrine ORM query builder or array
* $page - (integer) page to display
* $limit - (int|null) items to show. Possible values:
    * -1 - fetch all items (limit will be omitted)
    * null - default limit value will be used
    * integer positive - this value will be used to limited list items

#### Example

Paginate items with Doctrine ORM query builder.
```php
// Acme\ProjectBundle\Controller\ProjectController.php

public function listAction($page, Request $request)
{
    $repository = $this->getDoctrine()->getRepository('AcmeProjectBundle:Project');
    $qb = $repository->createQueryBuilder('p')
            ->select('p');

    $paginator = $this->get('arturdoruch_paginator');
    $projects = $paginator->paginate($qb, $page, 5);

    return $this->render('AcmeProjectBundle:Project:list.html.twig', array(
        'projects' => $projects
    ));
}
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
