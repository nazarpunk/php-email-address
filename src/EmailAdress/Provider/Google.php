<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Google extends Base
{
    public bool $nodot = true;
    public bool $noplus = true;

    public int $min = 6;
    public int $max = 30;

    public int $letter = 8;
}
