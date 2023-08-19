<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

use ReflectionClass;
use ReflectionException;

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
        public int     $letter,
        public ?bool   $tested,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public static function fromDomain(string $domain): Provider
    {
        static $data;
        if ($data === null) {
            $data = json_decode(file_get_contents('email.json', true), true);
            foreach ($data as $id => $v) {
                if (in_array($domain, $v['domains'])) {
                    $reflection = new ReflectionClass('EmailAdress\EmailAdress\Provider');
                    $instance = $reflection->newInstanceWithoutConstructor();
                    $properties = $reflection->getProperties();
                    foreach ($properties as $property) {
                        $pn = $property->getName();
                        if ($pn === 'id') {
                            $property->setValue($instance, $id);
                            continue;
                        }
                        $property->setValue($instance, $v[$property->getName()]);
                    }
                    return $instance;
                }
            }
        }

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
