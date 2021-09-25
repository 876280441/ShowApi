<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建用户
        $user = User::create([
            'name' => '超级管理员',
            'email' => 'admin@qq.com',
            'password' => bcrypt('123456')
        ]);
        //给用户分配角色
        $user->assignRole('admin');
    }
}
