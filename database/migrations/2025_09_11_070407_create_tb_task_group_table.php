<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_task_group', function (Blueprint $table) {
            // pakai INT agar konsisten dengan tb_task.uid (INT)
            $table->increments('uid');
            $table->string('groupId', 30)->unique();
            $table->string('groupName', 120);
            $table->text('groupNote')->nullable();
            $table->string('userName', 60)->nullable();

            // konsisten dengan pola existing: lastUpdated tanpa created_at/updated_at
            $table->timestamp('lastUpdated')->useCurrent()->useCurrentOnUpdate();

            // index tambahan utk pencarian cepat
            $table->index('groupName');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_task_group');
    }
};
