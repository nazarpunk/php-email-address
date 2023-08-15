<?php
declare(strict_types=1);

namespace EmailAdress\EmailAdress;

use EmailAdress\EmailAdress\Provider\Base;
use EmailAdress\EmailAdress\Provider\Google;
use EmailAdress\EmailAdress\Provider\Mailru;
use EmailAdress\EmailAdress\Provider\Yandex;

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

    /** local part of email */
    public string $local;

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

        $this->local = mb_strtolower($list[0]);
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
            default:
                $this->provider = new Base();
                // https://stackoverflow.com/a/16491074/12800371
                if (!preg_match('/^(?!-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->domain)) return EmailError::domain;
        }


        if ($this->provider->nodot) $this->local = str_replace('.', '', $this->local);
        if ($this->provider->noplus) {
            $pos = mb_strpos($this->local, '+');
            if ($pos !== false) $this->local = substr($this->local, 0, $pos);
        }

        $length = mb_strlen($this->local);
        if ($length < $this->provider->min) return EmailError::min;
        if ($length > $this->provider->max) return EmailError::max;

        if ($this->provider->letter > 0) {

        }


        return null;
    }
}
