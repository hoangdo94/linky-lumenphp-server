<?php
use Illuminate\Database\Seeder;
class MetaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meta')->insert([
            'link' => 'https://en.wikipedia.org/wiki/%22Hello,_World!%22_program',
            'title' => 'Hello, World!',
            'description' => '"Hello, World!" program',
            'thumb_id' => 1
        ]);
    }
}
