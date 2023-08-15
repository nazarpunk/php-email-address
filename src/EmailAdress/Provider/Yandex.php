<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Yandex extends Base
{
    public ?string $name = 'yandex';
    public int $max = 30;
    public string $first = /** @lang RegExp */
        '/^[a-z]/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9]$/';
}
