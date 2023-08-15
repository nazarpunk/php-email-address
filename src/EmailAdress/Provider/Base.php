<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Base
{
    public ?string $name = null;

    /** true: remove all dots */
    public bool $nodot = false;
    /** true: remove all after + symbols */
    public bool $noplus = false;
    /** true: prevent repeat ._- symbols */
    public bool $norepeat = false;
    public int $min = 1;
    public int $max = 64;
    /** count non letter symbol to need letter */
    public int $letter = 0;
    public string $symbols = /** @lang RegExp */
        '/^[a-z0-9._-]+$/';
    public string $first = /** @lang RegExp */
        '/^[a-z0-9]/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9_-]$/';
}
