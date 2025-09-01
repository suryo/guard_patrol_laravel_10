<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_person', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('personId', 2)->unique();
            $t->string('personName', 60);
            $t->string('userName', 60)->nullable();
            $t->string('isDeleted', 1)->default('0'); // '0' / '1'
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_person'); }
};

