<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Dr. Mourad Mezache',
                'email' => 'admin@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Prof. Lydia Idir',
                'email' => 'lydia.idir@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Prof. Bachir Saaidia',
                'email' => 'bachir.saaidia@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Prof. Amel Afia',
                'email' => 'amel.afia@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Prof. Sihem Aimeur',
                'email' => 'sihem.aimeur@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Prof. Karim Benali',
                'email' => 'karim.benali@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Prof. Fatima Zohra',
                'email' => 'fatima.z@institute.dz',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $rooms = [
            ['name' => 'Amphithéâtre Pasteur', 'description' => 'Grand amphi en gradins d’une capacité de 250 places assises.'],
            ['name' => 'Salle de Conférence', 'description' => 'Espace de 100 m² avec une configuration de 60 places en style théâtre.'],
            ['name' => 'Laboratoire Alpha', 'description' => 'Espace de travaux pratiques spacieux avec 24 paillasses individuelles.'],
            ['name' => 'Laboratoire Bêta', 'description' => 'Salle de manipulation technique équipée de 20 postes de travail fixes.'],
            ['name' => 'Laboratoire Gamma', 'description' => 'Grand plateau technique de 120 m² pouvant accueillir 30 étudiants.'],
            ['name' => 'Salle de Cours 101', 'description' => 'Salle de cours magistral standard avec une capacité de 80 places.'],
            ['name' => 'Salle de Cours 102', 'description' => 'Salle de cours de taille moyenne offrant 50 places assises.'],
            ['name' => 'Salle de TD 201', 'description' => 'Petite salle pour travaux dirigés avec une capacité de 25 places.'],
            ['name' => 'Salle de TD 202', 'description' => 'Salle de TD modulaire de 40 m² prévue pour 20 étudiants.'],
            ['name' => 'Salle de TD 203', 'description' => 'Espace de travail en sous-groupe d’une capacité maximale de 15 places.'],
            ['name' => 'Salle de TD 204', 'description' => 'Salle de tutorat optimisée pour des groupes de 20 personnes.'],
        ];

        foreach ($rooms as $room) {
            Asset::create([
                'name' => $room['name'],
                'description' => $room['description'],
                'type' => 'room',
                'status' => 'available',
            ]);
        }

        $equipment = [
            ['name' => 'Microphone HF Sans Fil', 'description' => 'Système de micro-cravate professionnel pour l’Amphithéâtre Pasteur.'],
            ['name' => 'Oscilloscope Numérique', 'description' => 'Appareil de mesure 2 voies haute précision pour le Laboratoire de Physique.'],
            ['name' => 'Kit de Capteurs Vernier', 'description' => 'Ensemble de sondes (pH, CO2, Température) pour les paillasses de Chimie.'],
            ['name' => 'Casque VR Meta Quest 3', 'description' => 'Équipement de réalité virtuelle pour les simulations du Labo IA & Data Science.'],
            ['name' => 'Vidéoprojecteur Mobile', 'description' => 'Unité portable haute luminosité pour les salles de TD non équipées.'],
            ['name' => 'Station de Soudage Weller', 'description' => 'Poste de soudure de précision pour les circuits du Labo Réseaux & Cyber.'],
            ['name' => 'Tablette Graphique Wacom', 'description' => 'Outil d’annotation numérique pour les présentations en Salle de Conférence.'],
            ['name' => 'Valise de Robotique', 'description' => 'Kit complet comprenant moteurs et micro-contrôleurs pour les projets de TD.'],
        ];

        foreach ($equipment as $item) {
            Asset::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'type' => 'equipment',
                'status' => 'available',
            ]);
        }
    }
}
