<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, change the column to VARCHAR to remove ENUM restriction
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'disabled_user'");
        
        // Then update existing roles to new system
        DB::table('users')->where('role', 'user')->update(['role' => 'disabled_user']);
        DB::table('users')->where('role', 'premium')->update(['role' => 'supporter_user']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'premium') DEFAULT 'user'");
    }
};
