<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\ForumCategory;
use App\Models\LegalPage;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\QaSession;
use App\Models\ResourceTemplate;
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
            [1, 'Conception du produit alimentaire', 'Développement de recette, choix du format et packaging, positionnement marché.', 'Fiche produit complète', false],
            [2, 'Calcul des coûts de revient', 'Ingrédients, emballage, main-d\'œuvre, transport et logistique.', 'Tableau de coûts complet', false],
            [3, 'Fixation des prix et stratégie de marge', 'Analyse concurrence, calcul de marge optimale, stratégies de pricing.', 'Grille tarifaire validée', false],
            [4, 'Réglementation et normes alimentaires', 'Normes sanitaires ANADA/FDA, procédures de certification, étiquetage.', 'Dossier de certification', true],
            [5, 'Stratégie de lancement et distribution', 'Canaux de distribution, logistique de livraison, gestion des stocks.', 'Plan de distribution', true],
            [6, 'Marketing et vente pour produits alimentaires', 'Branding, marketing digital, techniques de vente B2B et B2C.', 'Plan marketing 90 jours', true],
        ];

        $keptModuleIds = [];
        foreach ($modulesData as [$order, $title, $desc, $deliverable, $premium]) {
            $module = Module::query()->where('order', $order)->first();
            if (! $module) {
                $module = new Module(['order' => $order]);
            }
            $module->fill([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $desc,
                'deliverable' => $deliverable,
                'order' => $order,
                'is_premium_only' => $premium,
                'is_published' => true,
            ]);
            // Avoid unique slug collisions with legacy rows
            $slugClash = Module::query()->where('slug', $module->slug);
            if ($module->exists) {
                $slugClash->where('id', '!=', $module->id);
            }
            if ($slugClash->exists()) {
                $module->slug = Str::slug($title).'-'.$order;
            }
            $module->save();
            $keptModuleIds[] = $module->id;

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
        Module::query()->whereNotIn('id', $keptModuleIds)->update(['is_published' => false]);


        $faqs = [
            ['À qui s\'adresse FoodLab Academy ?', 'À toute personne qui veut transformer une idée alimentaire en produit rentable : entrepreneur·e débutant·e, artisan, restaurateur, ou employé en reconversion. Aucune connaissance technique préalable n\'est requise.'],
            ['Faut-il déjà avoir un produit ?', 'Non. Le programme commence depuis l\'idée. On vous aide à identifier, développer et tester votre produit étape par étape, même si vous partez de zéro.'],
            ['Combien de temps dure le programme ?', 'Le parcours est conçu pour 30 jours à raison de 1h à 2h par jour. Mais vous avez accès à vie au contenu, vous pouvez donc progresser à votre propre rythme selon votre emploi du temps.'],
            ['Quels moyens de paiement acceptez-vous ?', 'Nous acceptons : Orange Money, MTN Money, Moov Money, Wave, et les cartes bancaires internationales via Stripe. Le paiement est sécurisé et vous recevez un reçu immédiatement.'],
            ['Y a-t-il une garantie ?', 'Oui. Nous offrons une garantie satisfait ou remboursé de 14 jours sans condition. Si le programme ne correspond pas à vos attentes, contactez-nous et nous vous remboursons intégralement.'],
            ['Puis-je accéder au contenu hors ligne ?', 'Les vidéos nécessitent une connexion internet. En revanche, tous les documents, templates et ressources téléchargeables peuvent être sauvegardés sur votre appareil pour un accès hors ligne.'],
            ['Vais-je obtenir un certificat ?', 'Oui, les membres Premium reçoivent un certificat officiel FoodLab Academy après validation des 6 modules. Il est généré automatiquement en PDF avec un QR code de vérification. Les membres Starter reçoivent un certificat de participation.'],
            ['Les formations sont-elles en français ?', 'Oui, 100% en français. Le programme est spécialement adapté aux réalités des marchés d\'Afrique francophone avec des exemples concrets tirés du Bénin, Côte d\'Ivoire, Sénégal, Cameroun, Togo et Burkina Faso.'],
            ['Puis-je passer de Starter à Premium plus tard ?', 'Absolument. Vous pouvez upgrader à tout moment. Votre progression Starter est conservée et vous accédez immédiatement aux fonctionnalités Premium dès le paiement effectué.'],
            ['Comment fonctionne le calculateur de coût de revient ?', 'Vous entrez vos coûts (ingrédients, emballage, main-d\'œuvre, transport) et le calculateur détermine automatiquement votre coût de revient par unité, le prix de vente recommandé selon votre marge cible, et votre seuil de rentabilité. La version Premium permet l\'export en PDF et Excel.'],
        ];
        Faq::query()->delete();
        foreach ($faqs as $i => [$q, $a]) {
            Faq::create([
                'question' => $q,
                'answer' => $a,
                'order' => $i + 1,
                'is_published' => true,
            ]);
        }

        $legals = [
            'cgv' => ['Conditions Générales de Vente', "<h2>Objet</h2><p>Les présentes CGV régissent l'accès aux formations FoodLab Academy et aux produits numériques associés (ex. kits PDF).</p><h2>Tarifs</h2><p>Les prix Starter (gratuit) et Premium sont indiqués TTC sur le site. Les kits TasteBox sont facturés séparément.</p><h2>Accès</h2><p>L'accès aux modules est activé après confirmation du paiement (ou immédiatement pour le plan Starter gratuit).</p><h2>Garanties</h2><p>Formation Academy : garantie satisfait ou remboursé de 14 jours. Produits TasteBox : garantie 7 jours, sauf mention contraire sur la fiche produit.</p>"],
            'mentions-legales' => ['Mentions légales', "<h2>Éditeur</h2><p>FoodLab Academy — formation pour métiers de bouche.</p><h2>Contact</h2><p>contact@foodlab.test</p>"],
            'confidentialite' => ['Politique de confidentialité', "<h2>Données collectées</h2><p>Compte, profil (pays, secteur, niveau), progression pédagogique et paiements.</p><h2>Finalités</h2><p>Fourniture du service LMS, facturation et support.</p><h2>Cookies</h2><p>Cookies techniques de session et préférences (bannière cookies).</p>"],
        ];
        foreach ($legals as $slug => [$title, $content]) {
            LegalPage::updateOrCreate(['slug' => $slug], compact('title', 'content'));
        }

        // --- Backlog V2 sample data ---
        $forumCats = [
            ['Questions générales', 'questions-generales', 'Présentations et questions libres.', 1],
            ['Coût de revient', 'cout-de-revient', 'Astuces calculateur et pricing.', 2],
            ['Succès & témoignages', 'succes', 'Partagez vos victoires business.', 3],
        ];
        foreach ($forumCats as [$name, $slug, $desc, $order]) {
            ForumCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => $desc, 'order' => $order, 'is_published' => true]
            );
        }

        QaSession::updateOrCreate(
            ['title' => 'Q&R FoodLab — session démo'],
            [
                'description' => 'Session mensuelle de questions-réponses avec l’équipe pédagogique.',
                'session_at' => now()->addDays(14)->setTime(18, 0),
                'visio_link' => 'https://meet.example.com/foodlab-qa',
                'replay_url' => null,
                'is_published' => true,
            ]
        );
        QaSession::updateOrCreate(
            ['title' => 'Q&R FoodLab — replay exemple'],
            [
                'description' => 'Exemple de session passée avec replay.',
                'session_at' => now()->subDays(30)->setTime(18, 0),
                'visio_link' => null,
                'replay_url' => 'https://example.com/replay-foodlab',
                'is_published' => true,
            ]
        );

        ResourceTemplate::updateOrCreate(
            ['title' => 'Fiche technique produit (modèle)'],
            [
                'description' => 'Template Excel/Google Sheets pour structurer une fiche technique.',
                'file_path' => null,
                'external_url' => 'https://docs.google.com/spreadsheets',
                'is_published' => true,
                'order' => 1,
            ]
        );
        ResourceTemplate::updateOrCreate(
            ['title' => 'Grille de suivi des marges'],
            [
                'description' => 'Suivez vos marges hebdomadaires par produit.',
                'file_path' => null,
                'external_url' => 'https://docs.google.com/spreadsheets',
                'is_published' => true,
                'order' => 2,
            ]
        );
    }
}
