<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_schedule', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('scheduleId', 20)->unique();
            $t->string('mappingId', 5)->nullable();
            $t->string('personId', 2);
            $t->string('activityId', 20)->nullable();
            $t->string('checkpointName', 60)->nullable();
            $t->string('scheduleName', 60)->nullable();
            $t->dateTime('scheduleStart')->nullable();
            $t->dateTime('scheduleEnd')->nullable();
            $t->date('scheduleDate')->nullable();
            $t->string('userName', 60)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
            // index
            $t->index(['personId','scheduleDate']);
        });
    }
    public function down(): void { Schema::dropIfExists('tb_schedule'); }
};

