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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('code', 32)->unique();
            $table->string('name', 160);
            $table->enum('type', ['udd', 'hospital']);
            $table->text('address');
            $table->string('phone', 30)->nullable();
            $table->string('operating_hours', 100)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index(['region_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
