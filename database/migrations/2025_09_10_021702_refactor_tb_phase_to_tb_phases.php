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
        Schema::rename('tb_phase', 'tb_phases');

        Schema::table('tb_phases', function (Blueprint $t) {
            if (!Schema::hasColumn('tb_phases', 'phaseName')) {
                $t->string('phaseName', 120)->nullable();
            }
            $t->unsignedBigInteger('schedule_group_uid')->nullable()->after('phaseId');
            $t->unsignedSmallInteger('phaseOrder')->default(0);
            $t->date('phaseDate')->nullable()->change(); // tetap boleh, mengikuti kebutuhan
            $t->foreign('schedule_group_uid')
                ->references('uid')->on('tb_schedule_group')
                ->nullOnDelete();
            $t->index(['schedule_group_uid', 'phaseDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_phases', function (Blueprint $table) {
            //
        });
    }
};
