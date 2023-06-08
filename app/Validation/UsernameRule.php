<?php

namespace App\Validation;

class UsernameRule
{
    public function alpha_numeric_underscore($value, ?string &$error = null): bool
    {
        if(preg_match('/^[a-zA-Z0-9_]+$/', $value)){
            return true;
        }
        $error = "Invalid username, only alpha-numeric characters and underscores.";
        return false;
    }
}

?>