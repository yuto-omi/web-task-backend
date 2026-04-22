<?php

namespace App\Modules\Task\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'phase_id',
        'parent_task_id',
        'assignee_id',
        'created_by',
        'title',
        'memo',
        'status',
        'priority',
        'type_tag',
        'due_date',
        'estimated_hours',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'due_date'         => 'date',
            'estimated_hours'  => 'decimal:1',
            'sort_order'       => 'integer',
        ];
    }

    // ----------------
    // リレーション
    // ----------------

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** 親タスク */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /** サブタスク（1階層のみ） */
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    // ----------------
    // スコープ
    // ----------------

    /** 個人タスクのみ（project_id = NULL） */
    public function scopePersonal(Builder $query): Builder
    {
        return $query->whereNull('project_id');
    }

    /** 指定ユーザーのタスク */
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assignee_id', $userId);
    }

    /** 未完了のタスク */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('status', '!=', 'done');
    }

    /** 今日期限のタスク */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_date', today());
    }

    // ----------------
    // ヘルパー
    // ----------------

    /** 個人タスクかどうか */
    public function isPersonal(): bool
    {
        return is_null($this->project_id);
    }

    /** 完了タスクかどうか */
    public function isCompleted(): bool
    {
        return $this->status === 'done';
    }
}
