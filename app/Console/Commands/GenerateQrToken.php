<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\qr_tokens;
use Throwable;

class GenerateQrToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-qr-token';
    protected $signature = 'generate:qr-token';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Génère quotidiennement un token QR unique valable 24h';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        try {
            DB::transaction(function () {
                // Désactiver les anciens tokens (optionnel : on peut les garder pour audit)
                qr_tokens::where('is_active', true)->update(['is_active' => false]);

                // Générer un token unique (retry si collision)
                do {
                    $token = Str::random(48); // longueur configurable
                    $exists = qr_tokens::where('token', $token)->exists();
                } while ($exists);

                $qr = qr_tokens::create([
                    'token' => $token,
                    'is_active' => true,
                    'valid_until' => now()->addDay(), // +24h depuis la génération
                ]);

                \Log::info("✅ QR token généré sur Railway : {$qr->token}");
                $this->info("Token généré: {$qr->token} (valable jusqu'à {$qr->valid_until})");
            });
             $this->info('QR token généré avec succès !');
            return Command::SUCCESS;
            // return 0;

        }
        catch (Throwable $e) {
            \Log::error('❌ Erreur lors de la génération du QR token : '.$e->getMessage());
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
