<?php

namespace Database\Seeders;

use App\Models\Todolist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodolistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todolist::insert([
            [
                'title' => 'Belajar Laravel',
                'desc' => 'Belajar Laravel',
                'is_done' => false
            ],
            [
                'title' => 'Belajar API',
                'desc' => 'Belajar API sanctum Laravel',
                'is_done' => true
            ]
        ]);
    }
}
