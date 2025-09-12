<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            $table->string('templateId', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tb_schedule_template', function (Blueprint $table) {
            $table->string('templateId', 11)->change();
        });
    }
};
