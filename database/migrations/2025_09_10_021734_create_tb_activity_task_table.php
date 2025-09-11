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
        Schema::create('tb_activity_task', function (Blueprint $t) {
            $t->bigIncrements('uid');

            // GANTI dari:
            // $t->unsignedBigInteger('activity_uid');
            // menjadi:
            $t->unsignedInteger('activity_uid');

            $t->unsignedInteger('task_uid'); // cek juga: kalau tb_task.uid = INT UNSIGNED
            $t->boolean('is_done')->default(false);
            $t->timestamp('checked_at')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();

            $t->unique(['activity_uid', 'task_uid']);
            $t->foreign('activity_uid')->references('uid')->on('tb_activities')->cascadeOnDelete();
            $t->foreign('task_uid')->references('uid')->on('tb_task')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_activity_task');
    }
};
