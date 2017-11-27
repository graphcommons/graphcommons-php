Graph Commons is a collaborative 'network mapping' platform and a knowledge base of relationships. You can map relationships at scale and unfold the mystery about complex issues that impact you and your community.

See more about [here](//graphcommons.com/about).

## Before Beginning

- Set autoloader properly or use [Composer](//getcomposer.org).
- Use PHP >= 7.1 (or see others [PHP < 7.1](//github.com/graphcommons/graphcommons-php7-archive), [PHP < 7.0](//github.com/graphcommons/graphcommons-php-archive)).
- Run each call in `try/catch` blocks.
- On README, `dump` means `var_dump()`, besides `?` means optional for function arguments and nullable for function returns.

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

## Configuration

Configuration is optional but you can provide all these;

```php
// Dumps all Request and Response stuff (usefull while dev stage). (@default)
bool $debug = false;

// Sets cURL options. (@default)
array $clientOptions = [
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

$api = new Api('<Yor API Key>' ?bool $debug = false, ?array $clientOptions = []);
```

#### API - Status
```php
// GET /status
dump $api->status(); // => ?object
```

#### API - Search
```php
// GET /search
dump $api->search('<Search Query>' ?array $uriParams = []); // => array
```

### Graph Object
```php
use GraphCommons\Thing\Graph;

$graph = new Graph($api);
```

#### Graph - Check
```php
// HEAD /graphs/:id
dump $graph->check('<ID>'); // => bool
```

#### Graph - Get
```php
// GET /graphs/:id
dump $graph->get('<ID>'); // => ?object
```

#### Graph - Create
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

#### Graph - Update
```php
// PUT /graphs/:id
dump $graph->update('<ID>', [
    'name'        => 'Test',
    'description' => 'Test description.',
    'subtitle'    => 'Test subtitle.',
]); // => ?object
```

#### Graph - Clear
```php
// PUT /graphs/:id/clear
dump $graph->clear('<ID>'); // => ?object
```

#### Graph - Create Signal
```php
// PUT /graphs/:id/add
dump $graph->createSignal('<ID>', [
    ['action'    => Graph::SIGNAL_CREATE_EDGE,
     'from_name' => 'Ahmet',
     'from_type' => 'Person',
     'to_name'   => 'Fatih',
     'to_type'   => 'Person',
     'name'      => 'COLLABORATED',
     'weight'    => 2]
]); // => ?object
```

#### Graph - Get Types
```php
// GET /graphs/:id/types
dump $graph->getTypes('<ID>'); // => ?object
```

#### Graph - Get Edges
```php
// GET /graphs/:id/edges
dump $graph->getEdges('<ID>', array $uriParams); // => ?object
```

#### Graph - Get Paths
```php
// GET /graphs/:id/paths
dump $graph->getPaths('<ID>', array $uriParams); // => ?object
```

#### Graph - Get Collab Filter
```php
// GET /graphs/:id/collab_filter
dump $graph->getCollabFilter('<ID>', array $uriParams); // => ?object
```

#### Graph - Search
```php
// GET /graphs/search
dump $api->search('<Search Query>' ?array $uriParams = []); // => array
```

#### Graph - Delete
```php
// DELETE /graphs/:id
dump $api->delete('<ID>'); // => ?object
```

### Node Object
```php
use GraphCommons\Thing\Node;

$node = new Node($api);
```

#### Node - Get
```php
// GET /nodes/:id
dump $node->get('<ID>'); // => ?object
```

#### Node - Search
```php
// GET /nodes/search
dump $node->search('<Search Query>' ?array $uriParams = []); // => array
```

### Hub Object
```php
use GraphCommons\Thing\Hub;

$hub = new Hub($api);
```

#### Hub - Get
```php
// GET /hubs/:id
dump $hub->get('<ID>'); // => ?object
```

#### Hub - Get Types
```php
// GET /hubs/:id/types
dump $hub->getTypes('<ID>'); // => ?object
```

#### Hub - Get Paths
```php
// GET /hubs/:id/paths
dump $hub->getPaths('<ID>', array $uriParams); // => ?object
```

#### Hub - Get Collab Filter
```php
// GET /hubs/:id/collab_filter
dump $hub->getCollabFilter('<ID>', array $uriParams); // => ?object
```

#### Hub - Search Graphs
```php
// GET /graphs/search (alias, with Hub ID)
dump $hub->searchGraphs('<ID>', '<Search Query>' ?array $uriParams = []); // => array
```

#### Hub - Search Nodes
```php
// GET /nodes/search (alias, with Hub ID)
dump $hub->searchNodes('<ID>', '<Search Query>' ?array $uriParams = []); // => array
```
