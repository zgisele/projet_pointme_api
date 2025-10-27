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
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('stagiaire_id');
            $table->string('motif');
            $table->text('description')->nullable();
            $table->enum('niveau', ['avertissement', 'suspension', 'exclusion'])->default('avertissement');
            $table->date('date_sanction')->default(now());
            $table->foreign('coach_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('stagiaire_id')->references('id')->on('users')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanctions');
    }
};
