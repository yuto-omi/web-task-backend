<?php

namespace App\Modules\Project\Controllers;

use App\Modules\Project\DTO\CreateProjectDTO;
use App\Modules\Project\DTO\UpdateProjectDTO;
use App\Modules\Project\Requests\StoreProjectRequest;
use App\Modules\Project\Requests\UpdateProjectRequest;
use App\Modules\Project\Requests\UpdateProjectStatusRequest;
use App\Modules\Project\Resources\ProjectResource;
use App\Modules\Project\UseCases\CreateProject;
use App\Modules\Project\UseCases\DeleteProject;
use App\Modules\Project\UseCases\GetProject;
use App\Modules\Project\UseCases\ListProjects;
use App\Modules\Project\UseCases\UpdateProject;
use App\Modules\Project\UseCases\UpdateProjectStatus;
use App\Modules\Project\ValueObjects\ProjectStatus;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController
{
    /** プロジェクト一覧 */
    public function index(ListProjects $useCase): AnonymousResourceCollection
    {
        $projects = $useCase->handle(auth()->id());

        return ProjectResource::collection($projects);
    }

    /** プロジェクト作成 */
    public function store(StoreProjectRequest $request, CreateProject $useCase): JsonResponse
    {
        $project = $useCase->handle(
            CreateProjectDTO::fromArray($request->validated()),
            auth()->id(),
        );

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(201);
    }

    /** プロジェクト詳細 */
    public function show(int $id, GetProject $useCase): JsonResponse
    {
        $project = $useCase->handle($id);

        return (new ProjectResource($project))->response();
    }

    /** プロジェクト更新 */
    public function update(int $id, UpdateProjectRequest $request, GetProject $getUseCase, UpdateProject $updateUseCase): JsonResponse
    {
        $project = $getUseCase->handle($id);
        $updated = $updateUseCase->handle($project, UpdateProjectDTO::fromArray($request->validated()));

        return (new ProjectResource($updated))->response();
    }

    /** プロジェクト削除（ソフトデリート） */
    public function destroy(int $id, GetProject $getUseCase, DeleteProject $deleteUseCase): JsonResponse
    {
        $project = $getUseCase->handle($id);
        $deleteUseCase->handle($project);

        return ApiResponse::message('プロジェクトを削除しました。');
    }

    /** ステータス変更 */
    public function updateStatus(int $id, UpdateProjectStatusRequest $request, GetProject $getUseCase, UpdateProjectStatus $useCase): JsonResponse
    {
        $project = $getUseCase->handle($id);
        $updated = $useCase->handle($project, new ProjectStatus($request->validated()['status']));

        return (new ProjectResource($updated))->response();
    }
}
