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

echo '<table><thead><tr>';

$reflect = new ReflectionClass((new Email('@ya.ru'))->provider);
$props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

foreach ($props as $prop) {
    echo '<th>' . $prop->name . '</th>';
}

echo '</tr></thead><tbody>';

foreach ([
             '@icloud.com',
             '@hey.com',
             '@gmx.com',
             '@tuta.io',
             '@proton.me',
             '@ukr.net',
             '@gmail.com',
             '@ya.ru',
             '@mail.ru',
             '@vk.com',
             '@ro.ru',
             '@yahoo.com',
             '@aol.com',
             '@hotmail.com',
             '@meta.ua',
             '@fastmail.com',
             '@lycos.com',
             '@hush.ai',
             '@fuck.com',
         ] as $email) {
    $p = (new Email($email))->provider;
    echo '<tr>';
    foreach ($props as $prop) {
        $m = $prop->name;
        $v = $p->$m;

        switch ($prop->getType()->getName()) {
            case 'bool':
                $v = $v === null ? '' : ($v ? '✅' : '❌');
        }

        echo '<td>' . $v . '</td>';
    }
    echo '</tr>';
}

echo '</tbody></table>';

$adress = new Email('g.-max@hey.com');

echo '<pre>';
var_dump($adress);
echo '</pre>';
