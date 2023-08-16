<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

class Provider
{
    public function __construct(
        public ?string $name,
        public int     $min,
        public int     $max,
        public string  $chars = /* @lang RegExp */ '/^[a-z0-9._-]+$/',
        public string  $first = /* @lang RegExp */ '/^[a-z0-9]/',
        public string  $last = /* @lang RegExp */ '/[a-z0-9_-]$/',
        public bool    $nodot = false,
        public bool    $details = true,
        public bool    $unique_schar = false,
        public int     $letter = 0,
    )
    {
    }
}
