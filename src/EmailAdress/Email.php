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

        $this->provider = Provider::fromDomain($this->domain);
        // https://stackoverflow.com/a/16491074/12800371
        if ($this->provider->id === null && !preg_match('/^(?!-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $this->domain)) return EmailError::domain;

        $p = $this->provider;

        if ($p->id !== null && $p->universal_domains) $this->domain = $p->domains[0];

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

        if (($p->letter ?? 0) > 0 && $p->letter <= mb_strlen($u) && preg_match('/^[^a-z]+$/', $u)) return EmailError::letter;

        $this->email = $u . '@' . $this->domain;

        return null;
    }
}
