<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

// https://en.wikipedia.org/wiki/Comparison_of_webmail_providers
// https://developer.roman.grinyov.name/blog/92

class Email
{
    public function __construct(string $email)
    {
        $this->origin = trim($email);
        $this->error = $this->sanitize();
    }

    /** error when parse */
    public ?EmailError $error = null;

    /** originally provided email */
    public string $origin;

    /** user part of email */
    public string $user;

    /** domain part of email */
    public string $domain;

    /** sanitized email */
    public string $email;

    /** email provider */
    public Provider $provider;

    // https://developer.roman.grinyov.name/blog/92

    private function sanitize(): ?EmailError
    {
        $list = explode('@', $this->origin);
        if (count($list) != 2) return EmailError::at;

        $this->user = mb_strtolower($list[0]);
        $this->domain = mb_strtolower($list[1]);

        switch ($this->domain) {
            case 'gmail.com':
            case 'googlemail.com':
                $this->domain = 'gmail.com';
                $this->provider = new Provider(
                    name: 'google',
                    min: 6,
                    max: 30,
                    chars: 'a-z0-9.',
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    no_dot: true,
                    dot_underscore: null,
                    dot_minus: null,
                    underscore_underscore: null,
                    underscore_minus: null,
                    minus_minus: null,
                    letter: 8
                );
                break;
            case 'ya.ru':
            case 'yandex.ru':
            case 'yandex.by':
            case 'yandex.com':
            case 'yandex.kz':
            case 'narod.ru':
                $this->domain = 'ya.ru';
                $this->provider = new Provider(
                    name: 'yandex',
                    min: 1,
                    max: 30,
                    chars: 'a-z0-9.-',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_underscore: null,
                    underscore_underscore: null,
                    underscore_minus: null,
                );
                break;
            case 'mail.ru':
            case 'internet.ru':
            case 'bk.ru':
            case 'inbox.ru':
            case 'list.ru':
                $this->provider = new Provider(
                    name: 'mail_ru',
                    min: 5,
                    max: 31,
                    first: 'a-z0-9',
                    last: 'a-z0-9'
                );
                break;
            case 'vk.com':
                $this->provider = new Provider(
                    name: 'vk',
                    min: 5,
                    max: 31,
                    first: 'a-z0-9',
                    last: 'a-z0-9'
                );
                break;
            case 'ro.ru':
            case 'r0.ru':
            case 'rambler.ru':
            case 'rambler.ua':
            case 'autorambler.ru':
            case 'myrambler.ru':
                $this->provider = new Provider(
                    name: 'rambler',
                    min: 3,
                    max: 32,
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    details: false
                );
                break;
            case 'yahoo.com':
            case 'ymail.com':
            case 'rocketmail.com':
                $this->provider = new Provider(
                    name: 'yahoo',
                    min: 4,
                    max: 32,
                    chars: 'a-z0-9._',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_minus: null,
                    underscore_minus: null,
                    minus_minus: null,
                    details: false
                );
                break;
            case 'aol.com':
                $this->provider = new Provider(
                    name: 'aol',
                    min: 3,
                    max: 32,
                    chars: 'a-z0-9._',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_minus: null,
                    underscore_minus: null,
                    minus_minus: null,
                    many_dot: false,
                    details: false
                );
                break;
            case 'outlook.com':
            case 'hotmail.com':
                $this->provider = new Provider(
                    name: 'microsoft',
                    min: 1,
                    max: 64,
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                );
                break;
            case 'lycos.com':
                $this->provider = new Provider(
                    name: 'lycos',
                    min: 1,
                    max: 32,
                    chars: 'a-z0-9._-',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_digit: false,
                    underscore_underscore: true,
                    underscore_minus: true,
                    details: false,
                );
                break;
            case 'meta.ua':
                $this->provider = new Provider(
                    name: 'meta_ua',
                    min: 1,
                    max: 32,
                    chars: 'a-z0-9._-',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_digit: false,
                    underscore_underscore: true,
                    underscore_minus: true,
                    details: false,
                );
                break;
            default:
                $this->provider = new Provider(
                    name: null,
                    min: 1,
                    max: 64,
                    dot_dot: true,
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                    details: false,
                );
                // https://stackoverflow.com/a/16491074/12800371
                if (!preg_match('/^(?!-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->domain)) return EmailError::domain;
        }


        $p = $this->provider;
        $u = $this->user;

        if ($p->no_dot) $u = str_replace('.', '', $u);
        if ($p->details) {
            $pos = mb_strpos($u, '+');
            if ($pos !== false) $u = substr($u, 0, $pos);
        }

        $length = mb_strlen($u);
        if ($length < $p->min) return EmailError::min;
        if ($length > $p->max) return EmailError::max;

        if (!preg_match('/^[' . $p->chars . ']+$/', $u)) return EmailError::chars;
        if (!preg_match('/^[' . $p->first . ']/', $u)) return EmailError::first;
        if (!preg_match('/[' . $p->last . ']$/', $u)) return EmailError::last;

        if (!($p->dot_dot ?? true) && str_contains($u, '..')) return EmailError::dot_dot;
        if (!($p->dot_underscore ?? true) && str_contains($u, '._') || str_contains($u, '_.')) return EmailError::dot_underscore;
        if (!($p->dot_minus ?? true) && str_contains($u, '.-') || str_contains($u, '-.')) return EmailError::dot_minus;
        if (!$p->dot_digit && preg_match('/\.\d|\d\./', $u)) return EmailError::dot_digit;

        if (!($p->underscore_underscore ?? true) && str_contains($u, '__')) return EmailError::underscore_underscore;
        if (!($p->underscore_minus ?? true) && str_contains($u, '_-') || str_contains($u, '-_')) return EmailError::underscore_minus;

        if (!($p->minus_minus ?? true) && str_contains($u, '--')) return EmailError::minus_minus;

        if (!$p->many_dot && preg_match('/\.[\s\S]*\.|_[\s\S]*_/', $u)) return EmailError::many_dot;

        if ($p->letter > 0 && $p->letter <= mb_strlen($u) && preg_match('/^[^a-z]+$/', $u)) return EmailError::letter;

        $this->email = $u . '@' . $this->domain;

        return null;
    }
}
