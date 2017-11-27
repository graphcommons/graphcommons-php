Graph Commons is a collaborative 'network mapping' platform and a knowledge base of relationships. You can map relationships at scale and unfold the mystery about complex issues that impact you and your community.

See more about [here](//graphcommons.com/about).

## Before Beginning

- Set autoloader properly or use [Composer](//getcomposer.org).
- Use PHP >= 7.1 (or see others [PHP < 7.1](//github.com/graphcommons/graphcommons-php7-archive), [PHP < 7.0](//github.com/graphcommons/graphcommons-php-archive)).
- Run each call in `try/catch` blocks.
- On README, `dump` means `var_dump`.

Notice: See Graph Commons's official documents [here](//graphcommons.github.io/api-v1/) before using this library.

## Installation

```php
// manual
require '<Path to GraphCommons>/src/Autoload.php';

use GraphCommons\Autoload;

Autoload::register();
```

```bash
composer require graphcommons/graphcommons
```

```js
// composer.json
{"require": {"graphcommons/graphcommons": "~2.0"}}
```

## Configuration (Client Options)

Configuration is actually cURL options and optional but you can provide all these;

```php
$clientOptions = [
    'redir'          => true, // follow location
    'redirMax'       => 3,    // follow location max
    'timeout'        => 5,    // read timeout
    'timeoutConnect' => 3,    // connect timeout
];
```

## Usage

Notice: If any error, all (caller) methods below will throw `GraphCommons\ClientException` due to using `GraphCommons\Client::send()` method that makes call to Graph Commons API. So please, use `try/catch` blocks while making your calls, not regarding this usage examples.

### API Object
```php
use GraphCommons\Api;

$api = new Api('<Yor API Key>' /*, bool $debug = false, array $clientOptions = [] */);
```

#### Status
```php
// GET /status
dump $api->status(); // => ?object
```

#### Search
```php
// GET /search
dump $api->search('Search Query' /*, array $uriParams = [] */); // => ?array
```

### Graph Object
```php
use GraphCommons\Thing\Graph;

$graph = new Graph($api);
```

#### Check
```php
// HEAD /graphs/:id
dump $graph->check('<id>'); // => bool
```

#### Get
```php
// GET /graphs/:id
dump $graph->get('<id>'); // => ?object
```

#### Create
```php
// POST /graphs
dump $graph->create([
    'name'        => 'Test',
    'description' => '',
    'status'      => Graph::STATUS_DRAFT,
    'signals'     => [
        ['action'    => Graph::SIGNAL_CREATE_EDGE,
         'from_name' => 'Ahmet',
         'from_type' => 'Person',
         'to_name'   => 'Burak',
         'to_type'   => 'Person',
         'name'      => 'COLLABORATED',
         'weight'    => 2]
    ]
]); // => ?object
```
