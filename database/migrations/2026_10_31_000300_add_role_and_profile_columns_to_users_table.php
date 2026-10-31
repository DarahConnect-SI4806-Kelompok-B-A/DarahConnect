<?php

use App\Enums\UserRole;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 32)
                ->default(UserRole::PENDONOR->value)
                ->after('email');
            $table->string('phone', 25)
                ->nullable()
                ->unique()
                ->after('role');

            $table->foreignId('region_id')
                ->nullable()
                ->after('password')
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('facility_id')
                ->nullable()
                ->after('region_id')
                ->constrained('facilities')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])
                ->nullable()
                ->after('facility_id');
            $table->enum('rhesus', ['+', '-'])
                ->nullable()
                ->after('blood_type');
            $table->enum('gender', ['L', 'P'])
                ->nullable()
                ->after('rhesus');
            $table->date('birth_date')
                ->nullable()
                ->after('gender');
            $table->unsignedSmallInteger('weight_kg')
                ->nullable()
                ->after('birth_date');
            $table->date('last_donated_at')
                ->nullable()
                ->after('weight_kg');

            $table->text('address')
                ->nullable()
                ->after('last_donated_at');
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('address');
            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');
            $table->boolean('is_active')
                ->default(true)
                ->after('longitude');

            $table->index(['role', 'is_active']);
            $table->index(['blood_type', 'rhesus']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['blood_type', 'rhesus']);
            $table->dropConstrainedForeignId('facility_id');
            $table->dropConstrainedForeignId('region_id');
            $table->dropColumn([
                'role',
                'phone',
                'blood_type',
                'rhesus',
                'gender',
                'birth_date',
                'weight_kg',
                'last_donated_at',
                'address',
                'latitude',
                'longitude',
                'is_active',
            ]);
        });
    }
};
