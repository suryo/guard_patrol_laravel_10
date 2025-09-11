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
        Schema::rename('tb_schedule', 'tb_schedules');

        Schema::table('tb_schedules', function (Blueprint $t) {
            // buang kolom yang sekarang akan ditangani di tabel lain
            $t->dropColumn(['mappingId', 'activityId', 'checkpointName']);
            // pindahkan periode ke pivot (opsional): biarkan datetime jika ada data lama
            // jika ingin benar2 dihapus, uncomment:
            // $t->dropColumn(['scheduleStart','scheduleEnd']);
            // tambah kolom nama terstandar
            if (!Schema::hasColumn('tb_schedules', 'scheduleName')) {
                $t->string('scheduleName', 120)->nullable();
            }
            $t->index('scheduleDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_schedules', function (Blueprint $table) {
            //
        });
    }
};
