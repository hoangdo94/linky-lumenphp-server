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
        DB::table('user')->insert([
            'username' => 'admin',
            'email' => 'admin@linky.com',
            'password' => app('hash')->make('123123123'),
            'isAdmin' => 1
        ]);
        DB::table('user')->insert([
            'username' => 'test',
            'email' => 'test@linky.com',
            'password' => app('hash')->make('123123123')
        ]);
    }
}
