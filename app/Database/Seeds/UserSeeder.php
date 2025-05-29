<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'admin',
            'email'    => 'admin@admin.com',
            'password' => password_hash('admin', PASSWORD_BCRYPT)
        ];

        // Simple Queries
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name:, :email:, :password:)', $data);

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}