<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Dette;
use App\Models\Salaire;
use App\Models\Versement;
use Illuminate\Console\Command;

class VersementSalaireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versement:salaire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creation des salaires a chaque employee ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now();
        $userSalaires = Salaire::all();
        foreach ($userSalaires as $user) {
            $dettes = Dette::where('user_id', $user->user->id)->first();
            if ($dettes != null) {
                $has_det = 1;
                $detteMontant = $dettes->montant_total;
            } else {
                $has_det = 0;
                $detteMontant = 0;
            }
            $versements = Versement::create([
                'salaire_id' => $user->id,
                'montant_verse' => $user->montant,
                'mois' => $date->month,
                'has_dette' => $has_det,
                'dette_montant' => $detteMontant
            ]);
        }
        return $this->info("Salary payment saved with success");
    }
}
