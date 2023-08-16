<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Yahoo extends Base
{
    public ?string $name = 'yahoo';
    public bool $details = false;
    public int $min = 4;
    public int $max = 32;
    public bool $unique_schar = true;
    public string $chars = /** @lang RegExp */
        '/^[a-z0-9._]+$/';
    public string $first = /** @lang RegExp */
        '/^[a-z]/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9]$/';
}
