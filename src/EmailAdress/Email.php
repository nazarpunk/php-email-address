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

        if ($p->no_dot ?? false) $this->user = str_replace('.', '', $this->user);
        if ($p->details) {
            $pos = mb_strpos($this->user, '+');
            if ($pos !== false) $this->user = substr($this->user, 0, $pos);
        }

        $length = mb_strlen($this->user);
        if ($length < $p->min) return EmailError::min;
        if ($length > $p->max) return EmailError::max;

        if (!preg_match('/^[' . $p->chars . ']+$/', $this->user)) return EmailError::chars;
        if (!preg_match('/^[' . $p->first . ']/', $this->user)) return EmailError::first;
        if (!preg_match('/[' . $p->last . ']$/', $this->user)) return EmailError::last;

        if (!($p->dot_dot ?? true) && str_contains($this->user, '..')) return EmailError::dot_dot;
        if (!($p->dot_underscore ?? true) && (str_contains($this->user, '._') || str_contains($this->user, '_.'))) return EmailError::dot_underscore;
        if (!($p->dot_minus ?? true) && (str_contains($this->user, '.-') || str_contains($this->user, '-.'))) return EmailError::dot_minus;
        if (!($p->dot_digit ?? true) && preg_match('/\.\d|\d\./', $this->user)) return EmailError::dot_digit;

        if (!($p->underscore_underscore ?? true) && str_contains($this->user, '__')) return EmailError::underscore_underscore;
        if (!($p->underscore_minus ?? true) && (str_contains($this->user, '_-') || str_contains($this->user, '-_'))) return EmailError::underscore_minus;

        if (!($p->minus_minus ?? true) && str_contains($this->user, '--')) return EmailError::minus_minus;

        if (!($p->many_dot ?? true) && preg_match('/\.[\s\S]*\.|_[\s\S]*_/', $this->user)) return EmailError::many_dot;

        if (($p->letter ?? 0) > 0 && $p->letter <= mb_strlen($this->user) && preg_match('/^[^a-z]+$/', $this->user)) return EmailError::letter;

        $this->email = $this->user . '@' . $this->domain;

        return null;
    }
}
