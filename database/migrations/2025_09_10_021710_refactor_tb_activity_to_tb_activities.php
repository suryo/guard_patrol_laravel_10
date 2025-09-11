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
        Schema::rename('tb_activity', 'tb_activities');

        Schema::table('tb_activities', function (Blueprint $t) {
            // $t->unsignedBigInteger('phase_uid')->nullable()->after('activityId');
            $t->unsignedInteger('phase_uid')->nullable()->after('activityId');
            $t->string('personId', 100)->nullable()->change();
            $t->dateTime('activityStart')->nullable()->change();
            $t->dateTime('activityEnd')->nullable()->change();

            // hanya tambahkan FK + index
            $t->foreign('phase_uid')->references('uid')->on('tb_phases')->nullOnDelete();
            $t->index(['phase_uid', 'activityStart']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_activities', function (Blueprint $table) {
            //
        });
    }
};
