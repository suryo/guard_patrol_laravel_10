<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tb_person_mapping', function (Blueprint $t) {
            $t->increments('uid');
            $t->string('mappingId', 5)->unique();
            $t->string('mappingTag', 20)->nullable();
            $t->string('mappingName', 60);
            $t->string('userName', 60)->nullable();
            $t->timestamp('lastUpdated')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('tb_person_mapping'); }
};

