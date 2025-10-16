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
        Schema::create('users', function (Blueprint $table) {
            
            

            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('password');
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('photo')->nullable(); // chemin de la photo de profil
            $table->string('phone')->nullable();
            $table->string('promotion')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
             $table->enum('role', ['admin', 'coache', 'stagiaire'])->default('stagiaire');
            // $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
