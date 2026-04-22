<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // プロジェクト・フェーズ（NULLのとき個人タスク）
            $table->foreignId('project_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('phase_id')
                ->nullable()
                ->constrained('project_phases')
                ->nullOnDelete();

            // サブタスク（自己参照、1階層のみ）
            $table->foreignId('parent_task_id')
                ->nullable()
                ->constrained('tasks')
                ->nullOnDelete();

            // 担当者・作成者
            $table->foreignId('assignee_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('memo')->nullable();

            $table->enum('status', ['pending', 'in_progress', 'in_review', 'done'])
                ->default('pending');

            $table->enum('priority', ['low', 'medium', 'high'])->nullable();
            $table->string('type_tag')->nullable();

            $table->date('due_date')->nullable();
            $table->decimal('estimated_hours', 5, 1)->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
