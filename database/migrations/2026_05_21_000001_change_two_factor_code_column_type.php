<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'two_factor_code')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'mysql') {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('two_factor_code', 64)->nullable()->change();
            });

            return;
        }

        // MySQL may rebuild the table and re-validate all FKs; disable checks for this column-only change.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('ALTER TABLE `users` MODIFY `two_factor_code` VARCHAR(64) NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'two_factor_code')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'mysql') {
            Schema::table('users', function (Blueprint $table): void {
                $table->unsignedInteger('two_factor_code')->nullable()->change();
            });

            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('ALTER TABLE `users` MODIFY `two_factor_code` INT UNSIGNED NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
