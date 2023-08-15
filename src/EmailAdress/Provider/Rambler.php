<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Rambler extends Base
{
    public ?string $name = 'rambler';
    public bool $details = false;
    public int $min = 3;
    public int $max = 32;
    public string $first = /** @lang RegExp */
        '/[a-z0-9]$/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9]$/';

}
