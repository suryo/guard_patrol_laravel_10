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
        Schema::create('tb_groups', function (Blueprint $t) {
            $t->bigIncrements('uid');
            $t->string('groupId', 50)->unique();
            $t->string('groupName', 120)->nullable();     // label, mis. "Group [01:00 - 01:59]"
            $t->time('timeStart')->nullable();            // 01:00:00
            $t->time('timeEnd')->nullable();              // 01:59:59
            $t->text('note')->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
            $t->index(['timeStart', 'timeEnd']);
        });

        // // Panggil seeder langsung
        // \Artisan::call('db:seed', [
        //     '--class' => 'TbGroupSeeder',
        //     '--force' => true,   // penting kalau di production
        // ]);

        // // Panggil seeder langsung
        // \Artisan::call('db:seed', [
        //     '--class' => 'TbPhaseSeeder',
        //     '--force' => true,   // penting kalau di production
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_groups');
    }
};
