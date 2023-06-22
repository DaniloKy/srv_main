<?php

namespace Config;

use App\Validation\UsernameRule;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;


class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    public $createChar = [
        'character' => 'required',
        'character_name' => 'required|min_length[4]|max_length[16]|alpha_numeric_underscore',
    ];

    public $signup = [
        'username' => 'required|min_length[4]|max_length[16]|alpha_numeric_underscore',
        'email' => 'required|valid_email',
        'password' => 'required|min_length[4]|max_length[72]',
        'password_confirmation' => 'required|matches[password]',        
    ];

    public $user_update = [
        'username' => 'required|min_length[4]|max_length[16]|alpha_numeric_underscore',  
    ];

    public $createClass = [
        'name' => 'required|max_length[100]|alpha_numeric_underscore',
        'description' => 'required|max_length[500]',
        'image' => 'uploaded[image]|max_size[image, 1024]|ext_in[image,png,jpg,jpeg]',
    ];

    public $updateClass = [
        'name' => 'required|max_length[100]',
        'description' => 'required|max_length[500]',
    ];

    public $validImage = [
        'image' => 'max_size[image, 1024]|ext_in[image,png,jpg,jpeg]',
    ];
    
    

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        UsernameRule::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

}
