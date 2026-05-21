<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'two_factor_code')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_code', 64)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'two_factor_code')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('two_factor_code')->nullable()->change();
        });
    }
};
