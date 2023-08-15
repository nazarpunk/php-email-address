<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Google extends Base
{
    public ?string $name = 'google';
    public bool $nodot = true;
    public bool $noplus = true;
    public int $min = 6;
    public int $max = 30;
    public int $letter = 8;
    public string $symbols = /** @lang RegExp */
        '/^[a-z0-9]+$/';
    public string $first = /** @lang RegExp */
        '/^[a-z0-9]/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9]$/';
}
