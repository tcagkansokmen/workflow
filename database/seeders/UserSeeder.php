<?php declare(strict_types = 1);
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $email = "first@admin.com";
        $check = DB::table('users')->where('email', $email)->first();
        if(!$check){
            DB::table('users')
            ->insert([
                'name' => 'First',
                'surname' => 'Surname',
                'email' => 'first@admin.com',
                'password' => Hash::make('yntm2020**'),
                'group_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
