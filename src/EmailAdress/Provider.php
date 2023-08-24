<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

class Provider
{
    public function __construct(
        public ?string $id,
        public bool    $universal_domains,
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
        public ?int    $letter,
        public ?bool   $tested,
    )
    {
    }

    public static function fromDomain(string $domain): Provider
    {
        static $data;

        $data ??= json_decode(file_get_contents('email.json', true), false);

        foreach ($data as $id => $v) if (in_array($domain, $v->domains)) return new Provider(
            id: $id,
            universal_domains: $v->universal_domains,
            domains: $v->domains,
            min: $v->min,
            max: $v->max,
            chars: $v->chars,
            first: $v->first,
            last: $v->last,
            no_dot: $v->no_dot,
            dot_dot: $v->dot_dot,
            dot_underscore: $v->dot_underscore,
            dot_minus: $v->dot_minus,
            dot_digit: $v->dot_digit,
            underscore_underscore: $v->underscore_underscore,
            underscore_minus: $v->underscore_minus,
            minus_minus: $v->minus_minus,
            many_dot: $v->many_dot,
            details: $v->details,
            letter: $v->letter,
            tested: $v->tested,
        );

        return new Provider(
            id: null,
            universal_domains: false,
            domains: [],
            min: 1,
            max: 64,
            chars: 'a-z0-9._-',
            first: 'a-z0-9',
            last: 'a-z0-9_-',
            no_dot: false,
            dot_dot: true,
            dot_underscore: true,
            dot_minus: true,
            dot_digit: true,
            underscore_underscore: true,
            underscore_minus: true,
            minus_minus: true,
            many_dot: true,
            details: false,
            letter: 0,
            tested: null,
        );
    }
}
