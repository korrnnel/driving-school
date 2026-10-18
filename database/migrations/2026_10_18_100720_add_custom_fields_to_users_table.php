<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name');
            $table->string('phone')->unique()->after('email');
            $table->enum('role', ['admin', 'instructor', 'student'])->default('student')->after('password');
            $table->string('license_number', 7)->unique()->nullable()->after('role');

            $table->foreignId('assigned_vehicle_id')->unique()->nullable()->constrained('vehicles')->nullOnDelete()->after('license_number');

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['assigned_vehicle_id']);
            $table->dropColumn([
                'surname',
                'phone',
                'role',
                'license_number',
                'assigned_vehicle_id',
                'deleted_at'
            ]);
        });
    }
};
