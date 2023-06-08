<?php

namespace App\Validation;

class UsernameRule
{
    public function alpha_numeric_underscore($value, ?string &$error = null): bool
    {
        dd($value);
        return preg_match('/^[a-zA-Z0-9_]+$/', $value);
    }
}

?>