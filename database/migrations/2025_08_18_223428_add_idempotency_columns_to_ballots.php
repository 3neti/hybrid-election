<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ballots', function (Blueprint $table) {
            // 64-char hex sha256
            $table->string('payload_hash', 64)->nullable()->index();
            $table->string('source_ip', 45)->nullable();     // ipv4/ipv6
            $table->string('user_agent', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ballots', function (Blueprint $table) {
            $table->dropColumn(['payload_hash', 'source_ip', 'user_agent']);
        });
    }
};
