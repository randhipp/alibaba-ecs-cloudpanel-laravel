# Alibaba Cloud ECS User Panel 

### Laravel & Alibaba OpenAPI SDK PHP

Your end user will able to Start, Stop and Restart an Instance using this plain, ready to customize Web App not using Alibaba Console One.

![https://jombang.digital/](/storage/images/jdecspanel.png)

#### How To

- Clone This Repo

- Copy `.env.example` to .env => setup your database

- Go to `/database/seeds/` 
- Copy `UserTableSeeder.example.php` to `UserTableSeeder.php` 

```php
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
        // setup your user, ram secret key, and your instance. Note: do not use your master key, you must assign ram user for each end user.
        
        $user = App\User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'alibaba_key' => '___insert_key___',
            'alibaba_secret' => '_____insert_secret_____'
        ]);

        $instance = new App\Instance([
            'name' => 'ej02-windows-server-2012',
            'instance_id' => 'i-jkalsjdlasjdlasjd' // insert instance ID
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

```
   

- Run `composer install`
- Run `php artisan generate`
- Run `npm install && npm run dev`
- Run `php artisan migrate --seed`
- Run `php artisan optimize && php artisan serve`
- Your app will run at http://localhost:8000/
