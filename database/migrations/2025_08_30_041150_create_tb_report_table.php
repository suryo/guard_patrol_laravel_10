<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_report', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('reportId', 20)->unique();
            $t->decimal('reportLatitude',10,6)->nullable();
            $t->decimal('reportLongitude',10,6)->nullable();
            $t->string('activityId', 20)->nullable();
            $t->string('personId', 2);
            $t->string('checkpointName', 60)->nullable();
            $t->string('reportNote', 255)->nullable();
            $t->date('reportDate')->nullable();
            $t->time('reportTime')->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
            $t->index(['personId','reportDate']);
        });
    }
    public function down(): void { Schema::dropIfExists('tb_report'); }
};

