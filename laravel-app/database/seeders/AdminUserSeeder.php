<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // 既存ユーザーがいれば更新
            [
                'name' => '管理者',
                'password' => Hash::make('admin123'), // ハッシュ化
                'role' => 'admin',
            ]
            );
    }
}
