<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_activity', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('activityId', 20)->unique();
            $t->string('personId', 2);
            $t->string('scheduleId', 20)->nullable();
            $t->string('checkpointStart', 60)->nullable();
            $t->string('checkpointEnd', 60)->nullable();
            $t->dateTime('activityStart')->nullable();
            $t->dateTime('activityEnd')->nullable();
            $t->string('activityStatus', 1)->default('0'); // 0=planned,1=done,2=miss
            $t->timestamp('lastUpdated')->useCurrent();
            $t->index(['personId','scheduleId']);
        });
    }
    public function down(): void { Schema::dropIfExists('tb_activity'); }
};

