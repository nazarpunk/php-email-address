<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Base
{
    public bool $nodot = false;
    public bool $noplus = false;
    public int $min = 1;
    public int $max = 64;
    public int $letter = 0;
}
