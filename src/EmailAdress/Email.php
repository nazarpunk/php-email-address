<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

use EmailAdress\EmailAdress\Provider\Aol;
use EmailAdress\EmailAdress\Provider\Base;
use EmailAdress\EmailAdress\Provider\Google;
use EmailAdress\EmailAdress\Provider\Mailru;
use EmailAdress\EmailAdress\Provider\Rambler;
use EmailAdress\EmailAdress\Provider\Vk;
use EmailAdress\EmailAdress\Provider\Yahoo;
use EmailAdress\EmailAdress\Provider\Yandex;

// https://en.wikipedia.org/wiki/Comparison_of_webmail_providers

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
    public Base $provider;

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
                $this->provider = new Google();
                break;
            case 'ya.ru':
            case 'yandex.ru':
            case 'yandex.by':
            case 'yandex.com':
            case 'yandex.kz':
            case 'narod.ru':
                $this->domain = 'ya.ru';
                $this->provider = new Yandex();
                break;
            case 'mail.ru':
            case 'internet.ru':
            case 'bk.ru':
            case 'inbox.ru':
            case 'list.ru':
                $this->provider = new Mailru();
                break;
            case 'vk.com':
                $this->provider = new Vk(); // https://vk.com/faq18038
                break;
            case 'ro.ru':
            case 'rambler.ru':
            case 'rambler.ua':
            case 'autorambler.ru':
            case 'myrambler.ru':
                $this->provider = new Rambler();
                break;
            case 'yahoo.com':
            case 'ymail.com':
            case 'rocketmail.com':
                $this->provider = new Yahoo();
                break;
            case 'aol.com':
                $this->provider = new Aol();
                break;
            default:
                $this->provider = new Base();
                // https://stackoverflow.com/a/16491074/12800371
                if (!preg_match('/^(?!-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->domain)) return EmailError::domain;
        }


        if ($this->provider->nodot) $this->user = str_replace('.', '', $this->user);
        if ($this->provider->details) {
            $pos = mb_strpos($this->user, '+');
            if ($pos !== false) $this->user = substr($this->user, 0, $pos);
        }

        $length = mb_strlen($this->user);
        if ($length < $this->provider->min) return EmailError::min;
        if ($length > $this->provider->max) return EmailError::max;

        if (!preg_match($this->provider->chars, $this->user)) return EmailError::symbol;
        if (!preg_match($this->provider->first, $this->user)) return EmailError::first;
        if (!preg_match($this->provider->last, $this->user)) return EmailError::last;

        if ($this->provider->letter > 0 && $this->provider->letter <= mb_strlen($this->user) && preg_match('/^[^a-z]+$/', $this->user)) return EmailError::letter;

        if (preg_match('/[._-][._-]/', $this->user)) return EmailError::norepeat;

        if ($this->provider->unique_schar && preg_match('/\.[\s\S]*\.|_[\s\S]*_/', $this->user)) return EmailError::unique_schar;

        $this->email = $this->user . '@' . $this->domain;

        return null;
    }
}
