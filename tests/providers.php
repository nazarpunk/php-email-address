<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use EmailAdress\EmailAdress\Email;

echo <<<'EOF'
<style>
@import url('https://fonts.cdnfonts.com/css/jetbrains-mono');

:root{
color-scheme: dark;
font-family: 'JetBrains Mono', monospace;
}

pre {
font-family: 'JetBrains Mono', monospace;
}

table {
border-collapse: collapse;
}

th {
writing-mode: vertical-rl;
text-orientation: mixed;
}

td, th {
font-size: 16px;
border: 1px solid #2a6351;
padding: 6px 10px;
font-weight: normal;
}

body {
display: flex;
align-items: center;
flex-direction: row;
align-content: center;
justify-content: center;
gap: 1rem;
}

</style>
EOF;

$data = json_decode(file_get_contents('../src/EmailAdress/email.json', true), true);

echo '<table><thead><tr><th></th>';

function skip($n): bool
{
    return $n == 'domains';
}

foreach ($data[array_keys($data)[0]] as $k => $prop) {
    if (skip($k)) continue;
    echo '<th>' . $k . '</th>';
}

echo '</tr></thead><tbody>';


foreach ($data as $id => $values) {
    echo '<tr><td>' . $id . '</td>';
    foreach ($values as $k => $v) {
        if (skip($k)) continue;
        if ($v === null) $v = '';
        elseif (is_bool($v)) $v = $v ? '✅' : '❌';
        echo '<td>' . $v . '</td>';
    }
    echo '</tr>';
}


echo '</tbody></table>';

$adress = new Email('123@mailfence.com');

echo '<pre>';
var_dump($adress);
echo '</pre>';
