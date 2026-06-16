<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use App\Models\prestataire;
use App\Models\skills;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class PrestataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Array of freelancers matching the photo profiles
        $data = [
            [
                'nom' => 'Sy',
                'prenom' => 'Aminata',
                'email' => 'aminata.sy@etudserv.com',
                'telephone' => '36123456',
                'role' => 'etudiant',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Développement Web',
                'niveau' => 'junior',
                'note_moyenne' => 4.9,
                'skills' => ['React', 'Node.js', 'MongoDB'],
                'service_titre' => 'Développeuse Full Stack',
                'service_desc' => 'Étudiante en 3ème année à SUP\'NUM, spécialisée en développement web moderne.',
                'prix' => 5000
            ],
            [
                'nom' => 'Lemine',
                'prenom' => 'Mohamed',
                'email' => 'mohamed.lemine@etudserv.com',
                'telephone' => '36123457',
                'role' => 'freelancer',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Design Graphique',
                'niveau' => 'pro',
                'note_moyenne' => 5.0,
                'skills' => ['Figma', 'Adobe XD', 'Illustrator'],
                'service_titre' => 'Designer UI/UX',
                'service_desc' => 'Freelancer professionnel avec 3 ans d\'expérience en design d\'interfaces.',
                'prix' => 8000
            ],
            [
                'nom' => 'Diallo',
                'prenom' => 'Fatimata',
                'email' => 'fatimata.diallo@etudserv.com',
                'telephone' => '36123458',
                'role' => 'etudiant',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Traduction',
                'niveau' => 'junior',
                'note_moyenne' => 4.8,
                'skills' => ['Français', 'Arabe', 'Anglais'],
                'service_titre' => 'Traductrice FR/AR/EN',
                'service_desc' => 'Étudiante en langues, traductrice certifiée trilingue.',
                'prix' => 2000
            ],
            [
                'nom' => 'Ahmed',
                'prenom' => 'Sidi',
                'email' => 'sidi.ahmed@etudserv.com',
                'telephone' => '36123459',
                'role' => 'freelancer',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Marketing Digital',
                'niveau' => 'pro',
                'note_moyenne' => 4.7,
                'skills' => ['SEO', 'Google Ads', 'Facebook Ads'],
                'service_titre' => 'Expert Marketing Digital',
                'service_desc' => 'Spécialiste en croissance digitale pour PME et startups mauritaniennes.',
                'prix' => 6000
            ],
            [
                'nom' => 'Mint',
                'prenom' => 'Khadija',
                'email' => 'khadija.mint@etudserv.com',
                'telephone' => '36123460',
                'role' => 'etudiant',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Montage Vidéo',
                'niveau' => 'junior',
                'note_moyenne' => 4.9,
                'skills' => ['Premiere Pro', 'After Effects', 'DaVinci'],
                'service_titre' => 'Monteuse Vidéo',
                'service_desc' => 'Étudiante passionnée de cinéma, spécialisée dans le montage créatif.',
                'prix' => 3500
            ],
            [
                'nom' => 'Ba',
                'prenom' => 'Oumar',
                'email' => 'oumar.ba@etudserv.com',
                'telephone' => '36123461',
                'role' => 'freelancer',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Assistance Virtuelle',
                'niveau' => 'pro',
                'note_moyenne' => 4.6,
                'skills' => ['CRM', 'Email', 'Planning'],
                'service_titre' => 'Assistant Virtuel',
                'service_desc' => 'Assistant virtuel expérimenté, disponible 6j/7 pour vos besoins administratifs.',
                'prix' => 1500
            ],
            [
                'nom' => 'Ould',
                'prenom' => 'Isselmou',
                'email' => 'isselmou.ould@etudserv.com',
                'telephone' => '36123462',
                'role' => 'etudiant',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Développement Web',
                'niveau' => 'junior',
                'note_moyenne' => 4.8,
                'skills' => ['Flutter', 'React Native', 'Firebase'],
                'service_titre' => 'Développeur Mobile',
                'service_desc' => 'Étudiant en génie logiciel, développeur d\'apps mobiles cross-platform.',
                'prix' => 7000
            ],
            [
                'nom' => 'Thiam',
                'prenom' => 'Mariam',
                'email' => 'mariam.thiam@etudserv.com',
                'telephone' => '36123463',
                'role' => 'freelancer',
                'adresse' => 'Nouakchott',
                'type_prestataire' => 'Design Graphique',
                'niveau' => 'pro',
                'note_moyenne' => 5.0,
                'skills' => ['Photoshop', 'Canva', 'Branding'],
                'service_titre' => 'Graphiste & Illustratrice',
                'service_desc' => 'Graphiste freelance créative, spécialisée en identité visuelle pour TPE.',
                'prix' => 4000
            ]
        ];

        foreach ($data as $item) {
            // Check if user already exists
            $user = Utilisateur::where('email', $item['email'])->first();
            if (!$user) {
                $user = Utilisateur::create([
                    'nom' => $item['nom'],
                    'prenom' => $item['prenom'],
                    'email' => $item['email'],
                    'mot_de_passe' => Hash::make('password123'),
                    'telephone' => $item['telephone'],
                    'date_inscription' => date('Y-m-d'),
                    'adresse' => $item['adresse'],
                    'role' => $item['role']
                ]);
            }

            // Check if prestataire profile exists for this user
            $prestataire = prestataire::where('utilisateur_idutilisateur', $user->idutilisateur)->first();
            if (!$prestataire) {
                $prestataire = prestataire::create([
                    'type_prestataire' => $item['type_prestataire'],
                    'niveau' => $item['niveau'],
                    'note_moyenne' => $item['note_moyenne'],
                    'inscription_payee' => 1,
                    'utilisateur_idutilisateur' => $user->idutilisateur,
                    'bio' => $item['service_desc']
                ]);
            }

            // Sync skills
            $skillIds = [];
            foreach ($item['skills'] as $skillName) {
                $skill = skills::where('nom', $skillName)->first();
                if (!$skill) {
                    $skill = skills::create([
                        'nom' => $skillName,
                        'description' => 'Compétence en ' . $skillName
                    ]);
                }
                $skillIds[] = $skill->idskills;
            }
            $prestataire->skills()->sync($skillIds);

            // Create service if not exists
            $service = Service::where('prestataire_idprestataire', $prestataire->idprestataire)
                              ->where('titre', $item['service_titre'])->first();
            if (!$service) {
                $service = new Service();
                $service->titre = $item['service_titre'];
                $service->description = substr($item['service_desc'], 0, 40) . '...';
                $service->prix_base = $item['prix'];
                $service->actif = 1;
                $service->date_creation = date('Y-m-d');
                $service->skills_idskills = $skillIds[0]; // Set the required FK to the first skill
                $service->prestataire_idprestataire = $prestataire->idprestataire;
                $service->save();
            }
        }
    }
}
