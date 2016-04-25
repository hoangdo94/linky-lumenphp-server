<?php
use Illuminate\Database\Seeder;
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'name' => 'Technology'
        ]);
        DB::table('category')->insert([
            'name' => 'Photography'
        ]);
        DB::table('category')->insert([
            'name' => 'Life'
        ]);
        DB::table('category')->insert([
            'name' => 'Economy'
        ]);
    }
}
