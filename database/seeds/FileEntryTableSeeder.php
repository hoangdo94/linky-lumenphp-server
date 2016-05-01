<?php
use Illuminate\Database\Seeder;
class FileEntryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_entry')->insert([
            'filename' => 'default.png',
            'mime' => 'image/png',
            'original_filename' => 'default.png'
        ]);
    }
}
