<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress\Provider;

class Yandex extends Base
{
    public bool $noplus = true;
    public int $max = 30;
}
