<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'title' => 'Introduction à la Programmation',
            'slug' => 'introduction-a-la-programmation-' . uniqid(),
            'description' => 'Apprenez les bases de la programmation avec ce cours d\'introduction.',
            'level' => 'Débutant',
            'subject' => 'Informatique',
            'location' => 'En ligne',
            'mode' => 'online',
            'price' => 100,
            'tutor_id' => 1, 
        ]);

        Course::create([
            'title' => 'Développement Web Avancé',
            'slug' => 'developpement-web-avance-' . uniqid(),
            'description' => 'Perfectionnez vos compétences en développement web avec ce cours avancé.',
            'level' => 'Avancé',
            'subject' => 'Informatique',
            'location' => 'À distance',
            'mode' => 'online',
            'price' => 200,
            'tutor_id' => 1,
        ]);
    }
}
