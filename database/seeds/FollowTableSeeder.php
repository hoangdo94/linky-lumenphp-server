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
        DB::table('follow')->insert([
            'user_id' => '1',
            'follower_id' => '2'
        ]);
        DB::table('follow')->insert([
            'user_id' => '2',
            'follower_id' => '1'
        ]);
    }
}
