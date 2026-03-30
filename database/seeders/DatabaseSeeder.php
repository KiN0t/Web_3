<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Projet;
use App\Models\Ticket;
use App\Models\TempsPasse;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Crée 3 projets
        $projets = [
            ['nom' => 'Projet Alpha', 'client' => 'Acme Corp', 'statut' => 'actif', 'budget_heures' => 100, 'description' => 'Refonte du site vitrine.'],
            ['nom' => 'Projet Beta', 'client' => 'StartupXYZ', 'statut' => 'en-pause', 'budget_heures' => 50, 'description' => 'Application mobile.'],
            ['nom' => 'Projet Gamma', 'client' => 'BigStore', 'statut' => 'termine', 'budget_heures' => 30, 'description' => 'Intégration API paiement.'],
        ];

        foreach ($projets as $p) {
            $projet = Projet::create([...$p, 'user_id' => $user->id]);

            // Crée 3 tickets par projet
            $tickets = [
                ['titre' => 'Bug login', 'statut' => 'ouvert', 'priorite' => 'high', 'heures_estimees' => 5, 'facturable' => true, 'description' => 'Erreur 500 sur la page login.'],
                ['titre' => 'Amélioration UI', 'statut' => 'en-cours', 'priorite' => 'medium', 'heures_estimees' => 8, 'facturable' => true, 'description' => 'Revoir le design du dashboard.'],
                ['titre' => 'Mise à jour deps', 'statut' => 'ferme', 'priorite' => 'low', 'heures_estimees' => 2, 'facturable' => false, 'description' => 'Mettre à jour les packages.'],
            ];

            foreach ($tickets as $t) {
                $ticket = Ticket::create([...$t, 'projet_id' => $projet->id, 'user_id' => $user->id]);

                // Crée 2 temps passés par ticket
                TempsPasse::create([
                    'heures'      => 2,
                    'commentaire' => 'Première session de travail.',
                    'date'        => now()->subDays(3)->toDateString(),
                    'ticket_id'   => $ticket->id,
                    'user_id'     => $user->id,
                ]);
                TempsPasse::create([
                    'heures'      => 1.5,
                    'commentaire' => 'Suite du travail.',
                    'date'        => now()->subDay()->toDateString(),
                    'ticket_id'   => $ticket->id,
                    'user_id'     => $user->id,
                ]);
            }
        }
    }
} 