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
        Schema::create('tb_schedule_group', function (Blueprint $t) {
            $t->bigIncrements('uid');

            // GANTI baris ini:
            // $t->unsignedBigInteger('schedule_uid'); // SALAH untuk INT
            // JADI:
            $t->unsignedInteger('schedule_uid');      // MATCH ke tb_schedules.uid (INT UNSIGNED)

            $t->unsignedBigInteger('group_uid');      // MATCH ke tb_groups.uid (BIGINT UNSIGNED)

            $t->time('timeStart')->nullable();
            $t->time('timeEnd')->nullable();
            $t->unsignedSmallInteger('sortOrder')->default(0);
            $t->timestamps();

            $t->unique(['schedule_uid', 'group_uid']);
            $t->foreign('schedule_uid')->references('uid')->on('tb_schedules')->cascadeOnDelete();
            $t->foreign('group_uid')->references('uid')->on('tb_groups')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_schedule_group');
    }
};
