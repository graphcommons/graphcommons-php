<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

define('KEY', file_get_contents('./apikey'));
define('DEBUG', true);

require './src/Autoload.php';

use GraphCommons\Autoload;
use GraphCommons\Api;

Autoload::register();

$api = new Api(KEY, DEBUG);
// prs($api);
$r=$api->getClient()->get('/status', ["body"=>["a"=>1]]);
// prs($r);

// dumpers
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
