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
        Schema::create('tb_schedule_group_phase', function (Blueprint $t) {
            $t->bigIncrements('uid');
            $t->bigInteger('schedule_group_uid')->index(); // fk ke tb_schedule_group.uid
            $t->integer('phase_uid')->index();             // fk ke tb_phase.uid
            $t->date('phaseDate');                         // tanggal eksekusi phase
            $t->smallInteger('sortOrder')->default(1);
            $t->timestamps();
            // $t->unique(['schedule_group_uid','phase_uid','phaseDate']); // opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_schedule_group_phase');
    }
};
