<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_schedule_group_phase_activity', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_group_phase_uid')
                  ->after('uid')
                  ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tb_schedule_group_phase_activity', function (Blueprint $table) {
            $table->dropColumn('schedule_group_phase_uid');
        });
    }
};
