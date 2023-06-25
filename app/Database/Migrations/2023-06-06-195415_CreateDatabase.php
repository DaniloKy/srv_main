<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class CreateDatabase extends Migration
{
    public function up()
    {
        //$this->forge->dropDatabase("srv_utp", true);
        //$this->forge->createDatabase("srv_utp");

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "username" => [
                "type" => "VARCHAR",
                "constraint" => "16",
                "unique" => true,
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => "320",
                "unique" => true,
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => "72",
            ],
            "remember_token" => [
                "type" => "VARCHAR",
                "constraint" => "64",
                "null" => true,
                "unique" => true,
            ],
            "sId" => [
                "type" => "VARCHAR",
                "constraint" => "60",
                "null" => true,
            ],
            "verification_code" => [
                "type" => "VARCHAR",
                "constraint" => "8",
            ],
            "verification_sent_at" => [
                "type" => "TIMESTAMP",
            ],
            "status" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "default" => "0",
            ],
            "super" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "unsigned" => true,
                "default" => "0",
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
        ])->addPrimaryKey("id");

        if ($this->forge->createTable("users", true, ["ENGINE" => "InnoDB",]) === false) {
            throw new \RuntimeException("Could not create users table.");
        }

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "title_compiled" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "description" => [
                "type" => "TEXT",
            ],
            "image_path" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "null" => true,
            ],
            "created_by" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
        ])->addPrimaryKey("id");

        if ($this->forge->createTable("announcements", true, ["ENGINE" => "InnoDB",]) === false) {
            throw new \RuntimeException("Could not create users table.");
        }

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 3,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "tag" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "tag_compiled" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            
        ])->addPrimaryKey("id");

        if ($this->forge->createTable("tags", true, ["ENGINE" => "InnoDB",]) === false) {
            throw new \RuntimeException("Could not create users table.");
        }

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "announcement_id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
            ],
            "tag_id" => [
                "type" => "INT",
                "constraint" => 3,
                "unsigned" => true,
            ],
            
        ])->addPrimaryKey("id");

        if ($this->forge->createTable("announcements_tags", true, ["ENGINE" => "InnoDB",]) === false) {
            throw new \RuntimeException("Could not create users table.");
        }
        
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 3,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "name_compiled" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "nickname" => [
                "type" => "VARCHAR",
                "constraint" => "100",
            ],
            "description" => [
                "type" => "TEXT",
            ],
            "image_path" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "null" => true,
            ],
            
        ])->addPrimaryKey("id");

        if ($this->forge->createTable("classes", true, ["ENGINE" => "InnoDB",]) === false) {
            throw new \RuntimeException("Could not create users table.");
        }
    }

    public function down()
    {
        //
    }
}
