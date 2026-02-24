<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['username' => 'ownereli',      'password' => Hash::make('silverdawn'), 'role' => 'OWNER'],
            ['username' => 'admin_cherry',  'password' => Hash::make('xzshop123'),  'role' => 'ADMIN'],
            ['username' => 'admin_mir',     'password' => Hash::make('xzshop123'),  'role' => 'ADMIN'],
            ['username' => 'admin_sica',    'password' => Hash::make('xzshop123'),  'role' => 'ADMIN'],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['username' => $u['username']],
                ['password'=>$u['password'],'role'=>$u['role'],'created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
