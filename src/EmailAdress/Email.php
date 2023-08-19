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
                    id: 'google',
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
                    id: 'yandex',
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
                    id: 'mail_ru',
                    min: 5,
                    max: 31,
                    first: 'a-z0-9',
                    last: 'a-z0-9'
                );
                break;
            case 'vk.com':
                $this->provider = new Provider(
                    id: 'vk',
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
                    id: 'rambler',
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
                    id: 'yahoo',
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
                    id: 'aol',
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
            case 'icloud.com':
                $this->provider = new Provider(
                    id: 'icloud',
                    min: 3,
                    max: 20,
                    chars: 'a-z0-9._',
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_underscore: true,
                    dot_minus: null,
                    underscore_minus: null,
                    minus_minus: null,
                );
                break;
            case 'outlook.com':
            case 'hotmail.com':
                $this->provider = new Provider(
                    id: 'microsoft',
                    min: 1,
                    max: 64,
                    first: 'a-z',
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                );
                break;
            case 'lycos.com':
                $this->provider = new Provider(
                    id: 'lycos',
                    min: 1,
                    max: 32,
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_digit: false,
                    underscore_underscore: true,
                    underscore_minus: true,
                    details: false,
                    tested: false,
                );
                break;
            case 'meta.ua':
                $this->provider = new Provider(
                    id: 'meta_ua',
                    min: 1,
                    max: 32,
                    first: 'a-z',
                    last: 'a-z0-9',
                    dot_dot: true,
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                    details: false,
                );
                break;
            case 'proton.me':
            case 'protonmail.com':
                $this->provider = new Provider(
                    id: 'proton',
                    min: 1,
                    max: 40,
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    no_dot: true,
                );
                break;
            case 'tutanota.com':
            case 'tutanota.de':
            case 'tutamail.com':
            case 'tuta.io':
            case 'keemail.me':
                // a16657895ccb4f574c4e9bd148d5d204edb3e2549e8075ad65a61a4e2bc6fdba
                $this->provider = new Provider(
                    id: 'tutanota',
                    min: 3,
                    max: 64,
                    first: 'a-z0-9_',
                    last: 'a-z0-9_-',
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                    tested: false,
                );
                break;
            case 'gmx.com':
            case 'gmx.us':
                $this->provider = new Provider(
                    id: 'gmx',
                    min: 3,
                    max: 40,
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    details: false,
                );
                break;
            case 'hey.com':
                $this->provider = new Provider(
                    id: 'hey',
                    min: 2,
                    max: 64,
                    chars: 'a-z0-9.',
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    dot_underscore: null,
                    dot_minus: null,
                    underscore_underscore: null,
                    underscore_minus: null,
                    minus_minus: null,
                );
                break;
            case 'ukr.net':
                $this->provider = new Provider(
                    id: 'ukr_net',
                    min: 1,
                    max: 32,
                    first: 'a-z0-9_-',
                    last: 'a-z0-9_-',
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                );
                break;
            case 'fastmail.com':
            case 'fastmail.cn':
            case 'fastmail.co.uk':
            case 'fastmail.com.au':
            case 'fastmail.de':
            case 'fastmail.es':
            case 'fastmail.fm':
            case 'fastmail.fr':
            case 'fastmail.im':
            case 'fastmail.in':
            case 'fastmail.jp':
            case 'fastmail.mx':
            case 'fastmail.net':
            case 'fastmail.nl':
            case 'fastmail.org':
            case 'fastmail.se':
            case 'fastmail.to':
            case 'fastmail.tw':
            case 'fastmail.uk':
            case 'fastmail.us':
            case 'sent.com':
                $this->provider = new Provider(
                    id: 'fastmail',
                    min: 1,
                    max: 32,
                    chars: 'a-z0-9_',
                    first: 'a-z',
                    last: 'a-z0-9_',
                    no_dot: null,
                    dot_dot: null,
                    dot_underscore: null,
                    dot_minus: null,
                    dot_digit: null,
                    underscore_underscore: true,
                    underscore_minus: null,
                    minus_minus: null,
                    many_dot: null,
                );
                break;
            case 'hush.ai':
            case 'hush.com':
            case 'hushmail.com':
            case 'hushmail.me':
            case 'mac.hush.com':
                $this->provider = new Provider(
                    id: 'hush',
                    min: 1,
                    max: 45,
                    first: 'a-z0-9',
                    last: 'a-z0-9',
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_minus: true,
                    details: false,
                    tested: false,
                );
                break;
            default:
                $this->provider = new Provider(
                    id: null,
                    min: 1,
                    max: 64,
                    dot_dot: true,
                    dot_underscore: true,
                    dot_minus: true,
                    underscore_underscore: true,
                    underscore_minus: true,
                    minus_minus: true,
                    details: false,
                    tested: null,
                );
                // https://stackoverflow.com/a/16491074/12800371
                if (!preg_match('/^(?!-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->domain)) return EmailError::domain;
        }


        $p = $this->provider;
        $u = $this->user;

        if ($p->no_dot ?? false) $u = str_replace('.', '', $u);
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
        if (!($p->dot_underscore ?? true) && (str_contains($u, '._') || str_contains($u, '_.'))) return EmailError::dot_underscore;
        if (!($p->dot_minus ?? true) && (str_contains($u, '.-') || str_contains($u, '-.'))) return EmailError::dot_minus;
        if (!($p->dot_digit ?? true) && preg_match('/\.\d|\d\./', $u)) return EmailError::dot_digit;

        if (!($p->underscore_underscore ?? true) && str_contains($u, '__')) return EmailError::underscore_underscore;
        if (!($p->underscore_minus ?? true) && (str_contains($u, '_-') || str_contains($u, '-_'))) return EmailError::underscore_minus;

        if (!($p->minus_minus ?? true) && str_contains($u, '--')) return EmailError::minus_minus;

        if (!($p->many_dot ?? true) && preg_match('/\.[\s\S]*\.|_[\s\S]*_/', $u)) return EmailError::many_dot;

        if ($p->letter > 0 && $p->letter <= mb_strlen($u) && preg_match('/^[^a-z]+$/', $u)) return EmailError::letter;

        $this->email = $u . '@' . $this->domain;

        return null;
    }
}
