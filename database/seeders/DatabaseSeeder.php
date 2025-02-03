<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Member;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'koperasi',
            'username' => 'koperasi',
            'password' => 'koperasi',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'yayasan',
            'username' => 'yayasan',
            'password' => 'yayasan',
            'role' => 'yayasan',
        ]);

        $teachers = Teacher::all();
        $members = Member::all();
        if($teachers && !$members){
            foreach($teachers as $teacher){
                Member::create([
                    'reference' => $teacher->id,
                    'type' => 'teacher',
                    'name' => $teacher->name,
                ]);
            }
        }
    }
}
