<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

class Provider
{
    public function __construct(
        public ?string $id,
        public bool    $single_domain,
        public array   $domains,
        public int     $min,
        public int     $max,
        public string  $chars,
        public string  $first,
        public string  $last,
        public ?bool   $no_dot,
        public ?bool   $dot_dot,
        public ?bool   $dot_underscore,
        public ?bool   $dot_minus,
        public ?bool   $dot_digit,
        public ?bool   $underscore_underscore,
        public ?bool   $underscore_minus,
        public ?bool   $minus_minus,
        public ?bool   $many_dot,
        public bool    $details,
        public int     $letter,
        public ?bool   $tested,
    )
    {
    }
}
