<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$data = json_decode(file_get_contents('../src/EmailAdress/email.json', true), true);

echo '<style>:root{color-scheme: dark;}</style><pre>';

$n = chr(10);

$header = [''];

echo '| d |' . $n . '--------' . $n;

foreach ($data as $k => $v) {

    echo  $k . ' | ' . ($v['universal_domains'] ? '✅' : '❌') . ' | ' . $v['domains'][0] . $n;
    for ($i = 1; $i < count($v['domains']); $i++) {
        echo ' |  | ' . $v['domains'][$i]  . $n;
    }

}
