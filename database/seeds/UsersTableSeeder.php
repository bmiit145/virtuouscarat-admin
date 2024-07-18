<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\Illuminate\Support\Facades\DB::table('users')->count() > 0) {
            return;
        }

        $data=array(
            array(
                'name'=>'CodeAstro',
                'email'=>'admin@gmail.com',
                'password'=>Hash::make('password'),
                'role'=>'admin',
                'status'=>'active'
            ),
            array(
                'name'=>'Customer A',
                'email'=>'customer@mail.com',
                'password'=>Hash::make('password'),
                'role'=>'user',
                'status'=>'active'
            ),
        );

        DB::table('users')->insert($data);
    }
}
