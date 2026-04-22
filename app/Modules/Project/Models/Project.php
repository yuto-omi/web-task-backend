<?php

namespace App\Modules\Project\Models;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'name',
        'client_name',
        'status',
        'start_date',
        'deadline',
        'estimated_hours',
        'memo',
    ];

    protected function casts(): array
    {
        return [
            'start_date'      => 'date',
            'deadline'        => 'date',
            'estimated_hours' => 'decimal:1',
        ];
    }

    // ----------------
    // リレーション
    // ----------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('created_at');
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('sort_order');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ----------------
    // スコープ
    // ----------------

    /** 進行中のプロジェクト */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    /** 指定ユーザーが参加しているプロジェクト */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('members', fn ($q) => $q->where('user_id', $userId));
    }

    // ----------------
    // ヘルパー
    // ----------------

    /** 進捗率（完了タスク数 ÷ 全タスク数） */
    public function progressRate(): float
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return 0.0;
        }

        $done = $this->tasks()->where('status', 'done')->count();

        return round($done / $total * 100, 1);
    }
}
