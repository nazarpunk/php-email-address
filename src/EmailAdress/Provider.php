<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

class Provider
{
    public function __construct(
        public ?string $name,
        public int     $min,
        public int     $max,
        public string  $chars = 'a-z0-9._-',
        public string  $first = 'a-z0-9',
        public string  $last = 'a-z0-9_-',
        public bool    $no_dot = false,
        public ?bool   $dot_dot = false,
        public ?bool   $dot_underscore = false,
        public ?bool   $dot_minus = false,
        public bool    $dot_digit = true,
        public ?bool   $underscore_underscore = false,
        public ?bool   $underscore_minus = false,
        public ?bool   $minus_minus = false,
        public bool    $many_dot = true,
        public bool    $details = true,
        public int     $letter = 0,
    )
    {
    }
}
