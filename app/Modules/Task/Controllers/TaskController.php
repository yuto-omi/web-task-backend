<?php

namespace App\Modules\Task\Controllers;

use App\Modules\Task\DTO\CreateTaskDTO;
use App\Modules\Task\DTO\UpdateTaskDTO;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Requests\SortTasksRequest;
use App\Modules\Task\Requests\StoreTaskRequest;
use App\Modules\Task\Requests\UpdateTaskRequest;
use App\Modules\Task\Requests\UpdateTaskStatusRequest;
use App\Modules\Task\Resources\TaskResource;
use App\Modules\Task\UseCases\CreateTask;
use App\Modules\Task\UseCases\DeleteTask;
use App\Modules\Task\UseCases\GetTask;
use App\Modules\Task\UseCases\ListTasks;
use App\Modules\Task\UseCases\SortTasks;
use App\Modules\Task\UseCases\UpdateTask;
use App\Modules\Task\UseCases\UpdateTaskStatus;
use App\Modules\Task\ValueObjects\TaskStatus;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController
{

    use AuthorizesRequests;

    /**
     * タスク一覧
     * クエリパラメータ:
     *   - project_id: プロジェクトで絞り込み（nullで個人タスク）
     *   - phase_id: フェーズで絞り込み
     *   - assignee_id: 担当者で絞り込み
     *   - status: ステータスで絞り込み
     */
    public function index(Request $request, ListTasks $useCase): AnonymousResourceCollection
    {
        $filters = [];

        if ($request->has('project_id')) {
            $projectId = $request->query('project_id');
            $filters['project_id'] = ($projectId === 'null' || $projectId === null)
                ? null
                : (int) $projectId;
        }

        if ($request->filled('phase_id')) {
            $filters['phase_id'] = $request->phase_id;
        }

        if ($request->filled('assignee_id')) {
            $filters['assignee_id'] = $request->assignee_id;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $tasks = $useCase->handle($filters);

        return TaskResource::collection($tasks);
    }

    /** タスク作成 */
    public function store(StoreTaskRequest $request, CreateTask $useCase): JsonResponse
    {
        $this->authorize('create', Task::class);

        $validated = $request->validated();

        // assignee_id 未指定の場合はログインユーザー自身をアサイン（個人タスク用）
        $validated['assignee_id'] ??= auth()->id();

        $task = $useCase->handle(
            CreateTaskDTO::fromArray($validated),
            auth()->id(),
        );

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /** タスク詳細 */
    public function show(int $id, GetTask $useCase): JsonResponse
    {
        $task = $useCase->handle($id);

        return (new TaskResource($task))->response();
    }

    /** タスク更新 */
    public function update(int $id, UpdateTaskRequest $request, GetTask $getUseCase, UpdateTask $updateUseCase): JsonResponse
    {
        $task = $getUseCase->handle($id);
        $updated = $updateUseCase->handle($task, UpdateTaskDTO::fromArray($request->validated()));

        return (new TaskResource($updated))->response();
    }

    /**
     * タスク削除
     * 完了タスク → 物理削除
     * 未完了タスク → ソフトデリート
     */
    public function destroy(int $id, GetTask $getUseCase, DeleteTask $deleteUseCase): JsonResponse
    {
        $task = $getUseCase->handle($id);
        $deleteUseCase->handle($task);

        return ApiResponse::message('タスクを削除しました。');
    }

    /** ステータス変更 */
    public function updateStatus(int $id, UpdateTaskStatusRequest $request, GetTask $getUseCase, UpdateTaskStatus $useCase): JsonResponse
    {
        $task = $getUseCase->handle($id);
        $updated = $useCase->handle($task, new TaskStatus($request->validated()['status']));

        return (new TaskResource($updated))->response();
    }

    /** 並び順更新 */
    public function sort(SortTasksRequest $request, SortTasks $useCase): JsonResponse
    {
        $useCase->handle($request->validated()['ids']);

        return ApiResponse::message('並び順を更新しました。');
    }
}
