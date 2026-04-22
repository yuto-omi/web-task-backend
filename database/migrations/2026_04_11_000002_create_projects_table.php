<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('client_name')->nullable();

            $table->enum('status', ['not_started', 'in_progress', 'completed'])
                ->default('not_started');

            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->decimal('estimated_hours', 5, 1)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
