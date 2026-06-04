<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use App\Models\Booking;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Find an image file in database/seeders/images/ matching the asset name.
     * Tries jpg, jpeg, png, webp. Returns base64 data-URI string or null.
     */
    private function imageFor(string $assetName): ?string
    {
        $folder = database_path('seeders/images');
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];

        foreach ($extensions as $ext) {
            $path = $folder . DIRECTORY_SEPARATOR . $assetName . '.' . $ext;
            if (file_exists($path)) {
                $mime = match($ext) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png'         => 'image/png',
                    'webp'        => 'image/webp',
                };
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return null;
    }

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
                'name'        => $room['name'],
                'description' => $room['description'],
                'type'        => 'room',
                'status'      => 'available',
                'image'       => $this->imageFor($room['name']),
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
                'name'        => $item['name'],
                'description' => $item['description'],
                'type'        => 'equipment',
                'status'      => 'available',
                'image'       => $this->imageFor($item['name']),
            ]);
        }

        // ── Helpers ────────────────────────────────────────────────────────
        $today = Carbon::today();

        $lydia   = User::where('email', 'lydia.idir@institute.dz')->first();
        $bachir  = User::where('email', 'bachir.saaidia@institute.dz')->first();
        $amel    = User::where('email', 'amel.afia@institute.dz')->first();
        $sihem   = User::where('email', 'sihem.aimeur@institute.dz')->first();
        $karim   = User::where('email', 'karim.benali@institute.dz')->first();
        $fatima  = User::where('email', 'fatima.z@institute.dz')->first();

        $amphi       = Asset::where('name', 'Amphithéâtre Pasteur')->first();
        $conference  = Asset::where('name', 'Salle de Conférence')->first();
        $labAlpha    = Asset::where('name', 'Laboratoire Alpha')->first();
        $labBeta     = Asset::where('name', 'Laboratoire Bêta')->first();
        $labGamma    = Asset::where('name', 'Laboratoire Gamma')->first();
        $cours101    = Asset::where('name', 'Salle de Cours 101')->first();
        $cours102    = Asset::where('name', 'Salle de Cours 102')->first();
        $td201       = Asset::where('name', 'Salle de TD 201')->first();
        $td202       = Asset::where('name', 'Salle de TD 202')->first();
        $td203       = Asset::where('name', 'Salle de TD 203')->first();

        $micro       = Asset::where('name', 'Microphone HF Sans Fil')->first();
        $oscillo     = Asset::where('name', 'Oscilloscope Numérique')->first();
        $vernier     = Asset::where('name', 'Kit de Capteurs Vernier')->first();
        $vr          = Asset::where('name', 'Casque VR Meta Quest 3')->first();
        $projecteur  = Asset::where('name', 'Vidéoprojecteur Mobile')->first();
        $soudage     = Asset::where('name', 'Station de Soudage Weller')->first();
        $wacom       = Asset::where('name', 'Tablette Graphique Wacom')->first();
        $robotique   = Asset::where('name', 'Valise de Robotique')->first();

        // ── PAST BOOKINGS (last 7 days) ────────────────────────────────────

        // Karim — Amphi + Micro, 3 days ago 08:00–10:00
        Booking::insert([
            ['user_id' => $karim->id, 'asset_id' => $amphi->id,  'start_time' => $today->copy()->subDays(3)->setTime(8,0),  'end_time' => $today->copy()->subDays(3)->setTime(10,0), 'status' => 'active'],
            ['user_id' => $karim->id, 'asset_id' => $micro->id,  'start_time' => $today->copy()->subDays(3)->setTime(8,0),  'end_time' => $today->copy()->subDays(3)->setTime(10,0), 'status' => 'active'],
        ]);

        // Lydia — Lab Alpha + Vernier, 3 days ago 10:00–12:00
        Booking::insert([
            ['user_id' => $lydia->id, 'asset_id' => $labAlpha->id, 'start_time' => $today->copy()->subDays(3)->setTime(10,0), 'end_time' => $today->copy()->subDays(3)->setTime(12,0), 'status' => 'active'],
            ['user_id' => $lydia->id, 'asset_id' => $vernier->id,  'start_time' => $today->copy()->subDays(3)->setTime(10,0), 'end_time' => $today->copy()->subDays(3)->setTime(12,0), 'status' => 'active'],
        ]);

        // Bachir — Salle de Cours 101 + Projecteur, 2 days ago 09:00–11:00
        Booking::insert([
            ['user_id' => $bachir->id, 'asset_id' => $cours101->id,   'start_time' => $today->copy()->subDays(2)->setTime(9,0),  'end_time' => $today->copy()->subDays(2)->setTime(11,0), 'status' => 'active'],
            ['user_id' => $bachir->id, 'asset_id' => $projecteur->id, 'start_time' => $today->copy()->subDays(2)->setTime(9,0),  'end_time' => $today->copy()->subDays(2)->setTime(11,0), 'status' => 'active'],
        ]);

        // Amel — Lab Beta + Oscillo + Soudage, 2 days ago 13:00–15:00
        Booking::insert([
            ['user_id' => $amel->id, 'asset_id' => $labBeta->id,  'start_time' => $today->copy()->subDays(2)->setTime(13,0), 'end_time' => $today->copy()->subDays(2)->setTime(15,0), 'status' => 'active'],
            ['user_id' => $amel->id, 'asset_id' => $oscillo->id,  'start_time' => $today->copy()->subDays(2)->setTime(13,0), 'end_time' => $today->copy()->subDays(2)->setTime(15,0), 'status' => 'active'],
            ['user_id' => $amel->id, 'asset_id' => $soudage->id,  'start_time' => $today->copy()->subDays(2)->setTime(13,0), 'end_time' => $today->copy()->subDays(2)->setTime(15,0), 'status' => 'active'],
        ]);

        // Sihem — Conference + Wacom, yesterday 10:00–12:00
        Booking::insert([
            ['user_id' => $sihem->id, 'asset_id' => $conference->id, 'start_time' => $today->copy()->subDay()->setTime(10,0), 'end_time' => $today->copy()->subDay()->setTime(12,0), 'status' => 'active'],
            ['user_id' => $sihem->id, 'asset_id' => $wacom->id,      'start_time' => $today->copy()->subDay()->setTime(10,0), 'end_time' => $today->copy()->subDay()->setTime(12,0), 'status' => 'active'],
        ]);

        // Fatima — Lab Gamma + VR + Robotique, yesterday 14:00–16:00
        Booking::insert([
            ['user_id' => $fatima->id, 'asset_id' => $labGamma->id,  'start_time' => $today->copy()->subDay()->setTime(14,0), 'end_time' => $today->copy()->subDay()->setTime(16,0), 'status' => 'active'],
            ['user_id' => $fatima->id, 'asset_id' => $vr->id,        'start_time' => $today->copy()->subDay()->setTime(14,0), 'end_time' => $today->copy()->subDay()->setTime(16,0), 'status' => 'active'],
            ['user_id' => $fatima->id, 'asset_id' => $robotique->id, 'start_time' => $today->copy()->subDay()->setTime(14,0), 'end_time' => $today->copy()->subDay()->setTime(16,0), 'status' => 'active'],
        ]);

        // ── TODAY'S BOOKINGS ───────────────────────────────────────────────

        // Karim — Amphi + Micro, today 08:00–09:00
        Booking::insert([
            ['user_id' => $karim->id, 'asset_id' => $amphi->id, 'start_time' => $today->copy()->setTime(8,0),  'end_time' => $today->copy()->setTime(9,0),  'status' => 'active'],
            ['user_id' => $karim->id, 'asset_id' => $micro->id, 'start_time' => $today->copy()->setTime(8,0),  'end_time' => $today->copy()->setTime(9,0),  'status' => 'active'],
        ]);

        // Bachir — Cours 102 + Projecteur, today 10:00–12:00
        Booking::insert([
            ['user_id' => $bachir->id, 'asset_id' => $cours102->id,   'start_time' => $today->copy()->setTime(10,0), 'end_time' => $today->copy()->setTime(12,0), 'status' => 'active'],
            ['user_id' => $bachir->id, 'asset_id' => $projecteur->id, 'start_time' => $today->copy()->setTime(10,0), 'end_time' => $today->copy()->setTime(12,0), 'status' => 'active'],
        ]);

        // Lydia — TD 201, today 13:00–15:00
        Booking::insert([
            ['user_id' => $lydia->id, 'asset_id' => $td201->id,    'start_time' => $today->copy()->setTime(13,0), 'end_time' => $today->copy()->setTime(15,0), 'status' => 'active'],
            ['user_id' => $lydia->id, 'asset_id' => $robotique->id, 'start_time' => $today->copy()->setTime(13,0), 'end_time' => $today->copy()->setTime(15,0), 'status' => 'active'],
        ]);

        // Amel — TD 202, today 15:00–17:00
        Booking::insert([
            ['user_id' => $amel->id, 'asset_id' => $td202->id,   'start_time' => $today->copy()->setTime(15,0), 'end_time' => $today->copy()->setTime(17,0), 'status' => 'active'],
            ['user_id' => $amel->id, 'asset_id' => $wacom->id,   'start_time' => $today->copy()->setTime(15,0), 'end_time' => $today->copy()->setTime(17,0), 'status' => 'active'],
        ]);

        // ── UPCOMING BOOKINGS (next 3 days) ───────────────────────────────

        // Sihem — Lab Alpha + Vernier + Oscillo, tomorrow 09:00–11:00
        Booking::insert([
            ['user_id' => $sihem->id, 'asset_id' => $labAlpha->id, 'start_time' => $today->copy()->addDay()->setTime(9,0),  'end_time' => $today->copy()->addDay()->setTime(11,0), 'status' => 'active'],
            ['user_id' => $sihem->id, 'asset_id' => $vernier->id,  'start_time' => $today->copy()->addDay()->setTime(9,0),  'end_time' => $today->copy()->addDay()->setTime(11,0), 'status' => 'active'],
            ['user_id' => $sihem->id, 'asset_id' => $oscillo->id,  'start_time' => $today->copy()->addDay()->setTime(9,0),  'end_time' => $today->copy()->addDay()->setTime(11,0), 'status' => 'active'],
        ]);

        // Fatima — Salle de Conférence + Wacom, tomorrow 14:00–16:00
        Booking::insert([
            ['user_id' => $fatima->id, 'asset_id' => $conference->id, 'start_time' => $today->copy()->addDay()->setTime(14,0), 'end_time' => $today->copy()->addDay()->setTime(16,0), 'status' => 'active'],
            ['user_id' => $fatima->id, 'asset_id' => $wacom->id,      'start_time' => $today->copy()->addDay()->setTime(14,0), 'end_time' => $today->copy()->addDay()->setTime(16,0), 'status' => 'active'],
        ]);

        // Karim — TD 203 + VR, in 2 days 10:00–12:00
        Booking::insert([
            ['user_id' => $karim->id, 'asset_id' => $td203->id, 'start_time' => $today->copy()->addDays(2)->setTime(10,0), 'end_time' => $today->copy()->addDays(2)->setTime(12,0), 'status' => 'active'],
            ['user_id' => $karim->id, 'asset_id' => $vr->id,    'start_time' => $today->copy()->addDays(2)->setTime(10,0), 'end_time' => $today->copy()->addDays(2)->setTime(12,0), 'status' => 'active'],
        ]);

        // Bachir — Lab Beta + Soudage, in 2 days 13:00–15:00
        Booking::insert([
            ['user_id' => $bachir->id, 'asset_id' => $labBeta->id, 'start_time' => $today->copy()->addDays(2)->setTime(13,0), 'end_time' => $today->copy()->addDays(2)->setTime(15,0), 'status' => 'active'],
            ['user_id' => $bachir->id, 'asset_id' => $soudage->id, 'start_time' => $today->copy()->addDays(2)->setTime(13,0), 'end_time' => $today->copy()->addDays(2)->setTime(15,0), 'status' => 'active'],
        ]);

        // Lydia — Amphi + Micro + Projecteur, in 3 days 08:00–10:00
        Booking::insert([
            ['user_id' => $lydia->id, 'asset_id' => $amphi->id,      'start_time' => $today->copy()->addDays(3)->setTime(8,0),  'end_time' => $today->copy()->addDays(3)->setTime(10,0), 'status' => 'active'],
            ['user_id' => $lydia->id, 'asset_id' => $micro->id,      'start_time' => $today->copy()->addDays(3)->setTime(8,0),  'end_time' => $today->copy()->addDays(3)->setTime(10,0), 'status' => 'active'],
            ['user_id' => $lydia->id, 'asset_id' => $projecteur->id, 'start_time' => $today->copy()->addDays(3)->setTime(8,0),  'end_time' => $today->copy()->addDays(3)->setTime(10,0), 'status' => 'active'],
        ]);

        // ── REPORTS ────────────────────────────────────────────────────────

        // Pending: Amel reports oscilloscope issue
        Report::create([
            'user_id'             => $amel->id,
            'asset_id'            => $oscillo->id,
            'problem_description' => 'L\'oscilloscope ne s\'allume plus correctement. L\'écran reste noir après démarrage.',
            'possible_cause'      => 'Probable défaut d\'alimentation ou condensateur défectueux sur la carte principale.',
            'status'              => 'pending',
        ]);
        $oscillo->update(['status' => 'in_repair']);

        // Pending: Bachir reports projector issue
        Report::create([
            'user_id'             => $bachir->id,
            'asset_id'            => $projecteur->id,
            'problem_description' => 'Le vidéoprojecteur affiche une image déformée sur le côté droit, avec des lignes verticales.',
            'possible_cause'      => 'Choc probable lors du transport. La dalle DLP pourrait être endommagée.',
            'status'              => 'pending',
        ]);
        $projecteur->update(['status' => 'in_repair']);

        // Resolved: Fatima reported VR headset issue (already fixed)
        Report::create([
            'user_id'             => $fatima->id,
            'asset_id'            => $vr->id,
            'problem_description' => 'Le casque VR redémarre aléatoirement pendant les sessions de simulation.',
            'possible_cause'      => 'Surchauffe due à une mauvaise ventilation dans la salle. Batterie à vérifier.',
            'status'              => 'resolved',
        ]);
    }
}