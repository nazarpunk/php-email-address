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
    case many_dot;
    case dot_dot;
    case dot_minus;
    case dot_underscore;
    case dot_digit;
    case underscore_underscore;
    case underscore_minus;
    case minus_minus;
}
