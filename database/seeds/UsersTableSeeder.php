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
                'name'=>'vendor 1',
                'email'=>'vendor1@vc.com',
                'password'=>Hash::make('password'),
                'role'=>'user',
                'status'=>'active'
            ),
            array(
                'name'=>'vendor 2',
                'email'=>'vendor2@vc.com',
                'password'=>Hash::make('password'),
                'role'=>'user',
                'status'=>'active'
            ),
            array(
                'name'=>'vendor 3',
                'email'=>'vendor3@vc.com',
                'password'=>Hash::make('password'),
                'role'=>'user',
                'status'=>'active'
            ),
        );

        DB::table('users')->insert($data);
    }
}
