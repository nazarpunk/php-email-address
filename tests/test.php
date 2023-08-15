<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use EmailAdress\EmailAdress\Email;

echo '<pre>';
echo '<style>:root{color-scheme: dark}</style><pre>';

$adress = new Email('12345678@gmail.com');

var_dump($adress);

echo '</pre>';
