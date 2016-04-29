<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('FileEntryTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('TypeTableSeeder');
        $this->call('PostTableSeeder');
    }
}
