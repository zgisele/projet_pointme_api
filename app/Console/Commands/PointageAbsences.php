<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Pointage;
use Illuminate\Support\Facades\DB;

class PointageAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pointage-absences';
 

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Marquer automatiquement les absences journalières des stagiaires';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $dateDuJour = now()->toDateString();

        // Récupérer tous les stagiaires
        $stagiaires = User::where('role', 'stagiaire')->get();

        foreach ($stagiaires as $stagiaire) {

            // Vérifier si un pointage existe aujourd'hui
            $existe = Pointage::where('user_id', $stagiaire->id)
                ->whereDate('date_pointage', $dateDuJour)
                ->exists();

            // Si aucun pointage => enregistrer absence
            if (!$existe) {
                Pointage::create([
                    'user_id'        => $stagiaire->id,
                    'date_pointage'  => $dateDuJour,
                    'statut'         => 'absent',
                    'heure_arrivee'  => null,
                    'heure_sortie'   => null,
                    'note'           => 'Stagiaire Absent ',
                ]);
            }
        }

        $this->info("Absences générées pour la date : " . $dateDuJour);
   
    }
}
