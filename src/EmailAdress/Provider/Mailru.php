<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Mailru extends Base
{
    public ?string $name = 'mailru';
    public bool $noplus = true;
    public bool $norepeat = true;
    public int $min = 5;
    public int $max = 31;
    public string $first = /** @lang RegExp */
        '/[a-z0-9]$/';
    public string $last = /** @lang RegExp */
        '/[a-z0-9]$/';

}
