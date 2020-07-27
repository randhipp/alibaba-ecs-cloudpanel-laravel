<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'alibaba_key' => '',
            'alibaba_secret' => ''
        ]);

        $instance = new App\Instance([
            'name' => 'ej02-windows-server-2012',
            'instance_id' => 'i-'
        ]);
        $user->instances()->save($instance);

        App\User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true
        ]);
    }
}
