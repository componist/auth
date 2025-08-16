<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->timestamp('email_verified_at')->nullable();
            $table->integer('two_factor_code')->nullable();
            $table->timestamp('two_factor_expires_at')->nullable();
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropColumn('email_verified_at');
            $table->dropColumn('two_factor_code');
            $table->dropColumn('two_factor_expires_at');
            $table->dropColumn('last_login');
        });
    }
};