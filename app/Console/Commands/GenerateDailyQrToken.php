<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\qr_tokens;
use Illuminate\Support\Str;
use Carbon\Carbon;


class GenerateDailyQrToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-daily-qr-token';
    protected $signature = 'qr:generate-daily';
    

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Génère un token QR unique chaque jour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
         qr_tokens::where('is_active', true)->update(['is_active' => false]);

         qr_tokens::create([
            'token' => Str::random(32),
            'valid_until' => Carbon::now()->addDay(),
        ]);

        $this->info('Token QR quotidien généré avec succès.');
    
    }
}
