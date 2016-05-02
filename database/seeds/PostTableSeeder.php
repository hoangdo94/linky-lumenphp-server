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
            'num_likes' => 5,
            'content' => 'Good tutorial'
        ]);
        DB::table('post')->insert([
            'cate_id' => 2,
            'type_id' => 2,
            'user_id' => 2,
            'num_likes' => 3,
            'content' => 'Nice doc'
        ]);
        DB::table('post')->insert([
            'cate_id' => 3,
            'type_id' => 3,
            'user_id' => 1,
            'num_likes' => 1,
            'content' => 'documentation'
        ]);
        DB::table('post')->insert([
            'cate_id' => 4,
            'type_id' => 2,
            'user_id' => 2,
            'num_likes' => 8,
            'content' => 'laravel issue'
        ]);
    }
}
