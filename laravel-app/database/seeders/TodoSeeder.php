<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::create([
            'title' => '買い物に行く',
            'description' => '牛乳とパンを買う',
            'completed' => false,
        ]);

        Todo::create([
            'title' => '勉強する',
            'description' => 'Laravel の入門書を読む',
            'completed' => false,
        ]);

        Todo::create([
            'title' => '部屋の掃除',
            'description' => '机と本棚を整理する',
            'completed' => true,
        ]);
    }
}
