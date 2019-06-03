<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(User::class, 3)->create()->each(function($u) {
            $u->questions()
              ->saveMany(
                    factory(App\Question::class, rand(1, 5))->make()
              );
        });

    }
}



