<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to safely change the enum column to a varchar without needing doctrine/dbal
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) DEFAULT 'user'");

        // Update all existing buyers and sellers to simply be 'user'
        DB::table('users')->whereIn('role', ['buyer', 'seller'])->update(['role' => 'user']);
    }

    public function down(): void
    {
        // Cannot cleanly revert string back to enum without risking data loss
        // if there are roles other than the original ones.
    }
};
