<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
    Schema::create('gurus', function (Blueprint $table) {
        $table->id('id_guru');
        $table->string('nip', 20)->unique();
        $table->string('nama_guru', 100);
        $table->string('no_hp', 15)->nullable(); 
        $table->timestamps(); 
        $table->softDeletes();
    });}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
