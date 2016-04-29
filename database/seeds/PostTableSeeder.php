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
            'link' => 'http://coderexample.com/restful-api-in-lumen-a-laravel-micro-framework/',
            'content' => 'Good tutorial',
            'thumb_id' => 1
        ]);
        DB::table('post')->insert([
            'cate_id' => 2,
            'type_id' => 2,
            'user_id' => 2,
            'num_likes' => 3,
            'link' => 'https://lumen.laravel.com/docs/5.2/requests',
            'content' => 'Nice doc',
            'thumb_id' => 1
        ]);
        DB::table('post')->insert([
            'cate_id' => 3,
            'type_id' => 3,
            'user_id' => 1,
            'num_likes' => 1,
            'link' => 'https://laravel.com/docs/5.2/queries',
            'content' => 'documentation',
            'thumb_id' => 1
        ]);
        DB::table('post')->insert([
            'cate_id' => 4,
            'type_id' => 2,
            'user_id' => 2,
            'num_likes' => 8,
            'link' => 'https://github.com/laravel/framework/issues/8547',
            'content' => 'laravel issue',
            'thumb_id' => 1
        ]);
    }
}
