<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pointages', function (Blueprint $table) {
            
            $table->id(); // clé primaire auto-incrémentée
            $table->unsignedBigInteger('user_id'); // clé étrangère vers users
            // $table->unsignedBigInteger('qr_tokens_id')->nullable();
            $table->foreignId('qr_token_id')->nullable()->constrained('qr_tokens')->onDelete('set null');
            $table->enum('statut', ['present', 'retard', 'absent'])->default('absent');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_sortie')->nullable();
            $table->text('note')->nullable();
            $table->date('date_pointage');
            $table->timestamps();

            // Relations
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('qr_token_id')->references('id')->on('qr_tokens')->onDelete('set null');

            $table->unique(['user_id', 'date_pointage']); // 1 pointage par jour
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
