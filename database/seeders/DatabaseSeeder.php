<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\LegalPage;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@foodlab.test'],
            [
                'name' => 'Admin FoodLab',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country' => 'Bénin',
                'sector' => 'Formation',
                'level' => 'expert',
                'role' => 'admin',
                'plan' => 'premium',
                'plan_activated_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'starter@foodlab.test'],
            [
                'name' => 'Élève Starter',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country' => 'Côte d\'Ivoire',
                'sector' => 'Traiteur',
                'level' => 'débutant',
                'role' => 'student',
                'plan' => 'starter',
                'plan_activated_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'premium@foodlab.test'],
            [
                'name' => 'Élève Premium',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country' => 'Sénégal',
                'sector' => 'Boulangerie',
                'level' => 'intermédiaire',
                'role' => 'student',
                'plan' => 'premium',
                'plan_activated_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'free@foodlab.test'],
            [
                'name' => 'Prospect Gratuit',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country' => 'Togo',
                'sector' => 'Street food',
                'level' => 'débutant',
                'role' => 'student',
                'plan' => null,
            ]
        );

        $modulesData = [
            [1, 'Fondamentaux du coût de revient', 'Comprendre les bases du pricing alimentaire.', false],
            [2, 'Ingrédients et fiches techniques', 'Structurer vos recettes et coûts matières.', false],
            [3, 'Main d\'œuvre et charges', 'Intégrer le travail et les frais dans vos prix.', false],
            [4, 'Stratégie de marge Premium', 'Optimiser marges et positionnement.', true],
            [5, 'Pilotage et tableaux de bord', 'Suivre rentabilité et indicateurs clés.', true],
            [6, 'Mise à l\'échelle & certification', 'Industrialiser et valider vos acquis.', true],
        ];

        foreach ($modulesData as [$order, $title, $desc, $premium]) {
            $module = Module::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'description' => $desc,
                    'order' => $order,
                    'is_premium_only' => $premium,
                    'is_published' => true,
                ]
            );

            for ($i = 1; $i <= 2; $i++) {
                $lessonTitle = $title.' — Leçon '.$i;
                Lesson::updateOrCreate(
                    ['module_id' => $module->id, 'slug' => Str::slug($lessonTitle)],
                    [
                        'title' => $lessonTitle,
                        'content' => "<p>Contenu pédagogique de démonstration pour <strong>{$lessonTitle}</strong>.</p><p>Appliquez les notions avec le calculateur de coût de revient.</p>",
                        'order' => $i,
                        'video_provider' => 'bunny',
                        'video_id' => 'demo-video-'.$module->id.'-'.$i,
                        'duration' => 8 + $i,
                        'is_premium_only' => $premium,
                        'is_published' => true,
                    ]
                );
            }
        }

        $testimonials = [
            ['Aïcha K.', 'Fondatrice traiteur', 'Bénin', 'FoodLab m\'a permis de fixer enfin des prix rentables.', 5],
            ['Moussa D.', 'Gérant snack', 'Sénégal', 'Les modules sont clairs et adaptés à l\'Afrique de l\'Ouest.', 5],
            ['Fatou B.', 'Pâtissière', 'Côte d\'Ivoire', 'Le calculateur PDF est un vrai gain de temps.', 4],
        ];
        foreach ($testimonials as $i => [$name, $role, $country, $content, $rating]) {
            Testimonial::updateOrCreate(
                ['author_name' => $name, 'content' => $content],
                [
                    'author_role' => $role,
                    'country' => $country,
                    'rating' => $rating,
                    'is_published' => true,
                    'order' => $i + 1,
                ]
            );
        }

        $faqs = [
            ['Quels moyens de paiement acceptez-vous ?', 'Stripe (carte) et Mobile Money via KKiaPay / FedaPay selon configuration.'],
            ['Quelle est la différence Starter / Premium ?', 'Starter débloque les modules 1 à 3. Premium débloque les 6 modules, le parcours certifiant et le certificat QR.'],
            ['Puis-je upgrader plus tard ?', 'Oui, passez de Starter à Premium à tout moment depuis la page Plans.'],
            ['Le certificat est-il inclus ?', 'Le certificat PDF + QR est délivré automatiquement à la fin du parcours Premium.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(['question' => $q], [
                'answer' => $a,
                'order' => $i + 1,
                'is_published' => true,
            ]);
        }

        $legals = [
            'cgv' => ['Conditions Générales de Vente', "<h2>Objet</h2><p>Les présentes CGV régissent l'accès aux formations FoodLab Academy.</p><h2>Tarifs</h2><p>Les prix Starter et Premium sont indiqués TTC sur le site.</p><h2>Accès</h2><p>L'accès aux modules est activé après confirmation du paiement.</p>"],
            'mentions-legales' => ['Mentions légales', "<h2>Éditeur</h2><p>FoodLab Academy — formation pour métiers de bouche.</p><h2>Contact</h2><p>contact@foodlab.test</p>"],
            'confidentialite' => ['Politique de confidentialité', "<h2>Données collectées</h2><p>Compte, profil (pays, secteur, niveau), progression pédagogique et paiements.</p><h2>Finalités</h2><p>Fourniture du service LMS, facturation et support.</p><h2>Cookies</h2><p>Cookies techniques de session et préférences (bannière cookies).</p>"],
        ];
        foreach ($legals as $slug => [$title, $content]) {
            LegalPage::updateOrCreate(['slug' => $slug], compact('title', 'content'));
        }
    }
}
