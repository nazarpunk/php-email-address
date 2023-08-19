<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$data = json_decode(file_get_contents('../src/EmailAdress/email.json', true), true);

echo '<style>:root{color-scheme: dark;}</style><pre>';

function skip($v): bool
{
    return in_array($v, ['domains', 'universal_domains']);
}

$n = chr(10);

$header = [''];

foreach ($data[array_keys($data)[0]] as $k => $prop) {
    if (skip($k)) continue;
    $value = function ($text, $nk = null) use ($n, &$k): void {
        $k = $nk ?? $k;
        echo '- `' . ($nk ?? $k) . '` ' . $text . $n;
    };

    switch ($k) {
        case 'min':
            $value('минимальная длинна');
            break;
        case 'max':
            $value('максимальная длинна');
            break;
        case 'chars':
            $value('допустимые символы');
            break;
        case 'first':
            $value('допустимый первый символ');
            break;
        case 'last':
            $value('допустимый последний символ');
            break;
        case 'no_dot':
            $value('удалять ли точку при проверке', '!.');
            break;
        case 'dot_dot':
            $value('разрешена ли последовательность из двух точек', '..');
            break;
        case 'dot_underscore':
            $value('могут ли символы `.` и `_` находиться рядом', '._');
            break;
        case 'dot_minus':
            $value('** могут ли символы . и - находиться рядом', '.-');
            break;
        case 'dot_digit':
            $value('может ли точка соседствовать с цифрой', '.0');
            break;
        case 'underscore_underscore':
            $value('разрешена ли последовательность из двух `_`', '__');
            break;
        case 'underscore_minus':
            $value('могут ли символы `_` и `-` находиться рядом', '_-');
            break;
        case 'minus_minus':
            $value('разрешена ли последовательность из двух `-`', '--');
            break;
        case 'many_dot':
            $value('может ли в строке быть больше одной точки', '.+');
            break;
        case 'details':
            $value('игнорировать ли всё, что после знака `+`', '+');
            break;
        case 'letter':
            $value('количество не буквенных символов, после которого требуется минимум одна буква', 'l');
            break;
        case 'tested':
            $value('тестировались ли данные руками', 't');
            break;
    }

    $header[] = $k;
}

echo $n . '| ' . implode(' | ', $header) . ' |' . $n;

$hmark = [];
foreach ($header as $v) {
    $hmark[] = '---';
}
echo '| ' . implode(' | ', $hmark) . ' |' . $n;

foreach ($data as $id => $values) {
    $row = [$id];

    foreach ($values as $k => $v) {
        if (skip($k)) continue;
        if ($v === null) $v = '';
        elseif (is_bool($v)) $v = $v ? '✅' : '❌';
        $row[] = $v;
    }

    echo '| ' . implode(' | ', $row) . ' |' . $n;
}

