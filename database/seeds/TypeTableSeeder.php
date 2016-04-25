<?php
use Illuminate\Database\Seeder;
class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type')->insert([
            'name' => 'article'
        ]);
        DB::table('type')->insert([
            'name' => 'picture'
        ]);
        DB::table('type')->insert([
            'name' => 'video'
        ]);
    }
}
