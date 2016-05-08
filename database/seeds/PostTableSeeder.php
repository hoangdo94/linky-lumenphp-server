<?php
use Illuminate\Database\Seeder;
class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post')->insert([
            'cate_id' => 1,
            'type_id' => 1,
            'user_id' => 1,
            'num_likes' => 0,
            'meta_id' => 1,
            'content' => 'Hello World'
        ]);
    }
}
