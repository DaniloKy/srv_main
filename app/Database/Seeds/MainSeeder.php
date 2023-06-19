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
            'verification_code' => model(UsersModel::class)->makeVerfCode(),
            'status' => 1,
            'super' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ];
        $this->db->table('users')->insert($data_users);


        $this->db->table('tags')->insert(["tag" => "News", "tag_compiled" => "news"]);
        $this->db->table('tags')->insert(["tag" => "Patch Notes", "tag_compiled" => "patch_notes"]);
        
    }
}

?>