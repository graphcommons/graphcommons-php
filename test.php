<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

define('KEY', file_get_contents('./apikey'));
define('DEBUG', true);

require './src/Autoload.php';

use GraphCommons\Autoload;
use GraphCommons\Api;
use GraphCommons\Thing\{Graph};

Autoload::register();

$api = new Api(KEY, DEBUG);

// // GET /status
// $ret = $api->status();

$graph = new Graph($api);

// // HEAD /graphs/:id
// $ret = $graph->check('8f8c794a-a498-4c3e-a73b-95a460db6e3a');

// // GET /graphs/:id
// $ret = $graph->get('8f8c794a-a498-4c3e-a73b-95a460db6e3a');

// // POST /graphs
// $ret = $graph->create([
//     'name'        => 'Test',
//     'description' => '',
//     'status'      => Graph::STATUS_DRAFT,
//     'signals'     => [
//         ['action'    => Graph::SIGNAL_CREATE_EDGE,
//          'from_name' => 'Ahmet',
//          'from_type' => 'Person',
//          'to_name'   => 'Burak',
//          'to_type'   => 'Person',
//          'name'      => 'COLLABORATED',
//          'weight'    => 2]
//     ]
// ]);

// // PUT /graphs/:id
// $ret = $graph->update('b5081e44-40cc-4b82-ba0d-6f8985b7d7e8', [
//     'name'        => 'Test',
//     'description' => 'Test description.',
//     'subtitle'    => 'Test subtitle.',
// ]);

// // PUT /graphs/:id/clear
// $ret = $graph->clear('b5081e44-40cc-4b82-ba0d-6f8985b7d7e8');

// // PUT /graphs/:id/add
// $ret = $graph->createSignal('b5081e44-40cc-4b82-ba0d-6f8985b7d7e8', [
//     ['action'    => Graph::SIGNAL_CREATE_EDGE,
//      'from_name' => 'Ahmet',
//      'from_type' => 'Person',
//      'to_name'   => 'Fatih',
//      'to_type'   => 'Person',
//      'name'      => 'COLLABORATED',
//      'weight'    => 2]
// ]);

// // DELETE /graphs/:id
// $ret = $graph->delete('1b31ce51-14b4-4fb8-b689-9bd95793a47e');

prs($ret);



// @dump
function prs($input = '', $exit = false) {
    echo print_r($input, true) . PHP_EOL;
    if ($exit) {
        exit(0);
    }
}
function prd($input = '', $exit = false) {
    var_dump($input);
    if ($exit) {
        exit(0);
    }
}
