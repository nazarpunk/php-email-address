<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Base
{
    public ?string $name = null;
    public bool $nodot = false;
    public bool $details = true;
    public int $min = 1;
    public int $max = 64;
    public int $letter = 0;
    public bool $unique_schar = false;
    public string $chars = /** @lang RegExp */
        '/^[a-z0-9._-]+$/';
    public string $first = /** @lang RegExp */
        '/^[a-z0-9]/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9_-]$/';
}
