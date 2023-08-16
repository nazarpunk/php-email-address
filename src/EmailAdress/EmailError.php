<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

enum EmailError
{
    case at;
    case domain;
    case min;
    case max;
    case chars;
    case letter;
    case first;
    case last;
    case norepeat;
    case unique_schar;
}
