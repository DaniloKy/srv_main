<?php

namespace App\Database\Seeds;

use App\Models\UsersModel;
use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $data_users = [
            'username' => env('ADMIN_USERNAME'),
            'email'    => env('ADMIN_EMAIL'),
            'password' => password_hash(env('ADMIN_PASS'), PASSWORD_BCRYPT),
            'sId' => '',
            'verification_code' => model(UsersModel::class)->makeVerfCode(),
            'active' => 1,
            'super' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ];
        $this->db->table('users')->insert($data_users);

        $tags = ["news", "patches"];
        for($i = 0; $i < count($tags); $i++)
            $this->db->table('tags')->insert(["tag" => $tags[$i]]);
        
    }
}

?>