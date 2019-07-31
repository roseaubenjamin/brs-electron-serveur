<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{	
	/*
	 * Création de l'utilisateur admin-route de l'application 
	*/
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'heldino',
            'email' => 'ahheldino@gmail.com',
            'role' => 'root',
            'password' => bcrypt('ahheldino@gmail.com'),
        ]);
    }
}
