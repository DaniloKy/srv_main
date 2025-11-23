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
        $this->db->table('tags')->insert(["tag" => "Product", "tag_compiled" => "product"]);

        $productTagId = $this->db->insertID();

        $this->db->table('announcements')->insert([
            "title" => "Introducing the Game Logo Mug!",
            "title_compiled" => "introducing_the_game_logo_mug",
            "description" => "Enhance your gaming experience with the Game Logo Mug, featuring the sleek logo of Survive Utopia. Made with high-quality ceramic, it's the perfect collector's item for passionate gamers. Stay tuned for pre-order details! #SurviveUtopiaMug #GamingLifestyle",
            "image_path" => "mockup2.png",
            "tag_id" => $productTagId,
            "created_by" => 1,
            "created_at" => date("Y-m-d H:i:s")
        ]);

        $this->db->table('classes')->insert([
            "name" => "Archer",
            "name_compiled" => "archer",
            "nickname" => "The Silent Serpent",
            "description" => "Master of the bow, the Archer strikes from the shadows with deadly precision. Agile and elusive, they control the battlefield from a distance, raining death upon their foes before they are even seen.",
            "image_path" => "archer-background.png"
        ]);

        $this->db->table('classes')->insert([
            "name" => "Mage",
            "name_compiled" => "mage",
            "nickname" => "The Arcane Scholar",
            "description" => "Wielding the ancient arts, the Mage commands the elements to devastate foes. Fragile but destructive, their power is unmatched, bending reality itself to their will.",
            "image_path" => "wizard-background.jpg"
        ]);

        $this->db->table('classes')->insert([
            "name" => "Fighter",
            "name_compiled" => "fighter",
            "nickname" => "The Valiant Blade",
            "description" => "A warrior of unmatched strength and courage, the Fighter stands at the front lines. With heavy armor and powerful strikes, they are the shield and the sword, protecting their allies and crushing their enemies.",
            "image_path" => "fighter-background.jpg"
        ]);

        
        
    }
}

?>