<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use EmailAdress\EmailAdress\Provider;

$provider = new Provider(
    id: 'hush',
    single_domain: false,
    domains: [],
    min: 1,
    max: 45,
    chars: 'a-z0-9._-',
    first: 'a-z0-9',
    last: 'a-z0-9',
    no_dot: false,
    dot_dot: false,
    dot_underscore: true,
    dot_minus: true,
    dot_digit: true,
    underscore_underscore: false,
    underscore_minus: true,
    minus_minus: false,
    many_dot: true,
    details: false,
    letter: 0,
    tested: false,
);
echo '<style>:root{color-scheme: dark}</style><pre>';
$p = json_encode($provider, JSON_PRETTY_PRINT);


echo $p;
echo '</pre>';
