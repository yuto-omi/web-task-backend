<?php

namespace App\Modules\Project\Controllers;

use App\Modules\Project\Contracts\ProjectPhaseRepository;
use App\Modules\Project\DTO\CreatePhaseDTO;
use App\Modules\Project\DTO\UpdatePhaseDTO;
use App\Modules\Project\Requests\SortPhasesRequest;
use App\Modules\Project\Requests\StorePhaseRequest;
use App\Modules\Project\Requests\UpdatePhaseRequest;
use App\Modules\Project\Requests\UpdatePhaseStatusRequest;
use App\Modules\Project\Resources\ProjectPhaseResource;
use App\Modules\Project\UseCases\CreatePhase;
use App\Modules\Project\UseCases\DeletePhase;
use App\Modules\Project\UseCases\ListPhases;
use App\Modules\Project\UseCases\SortPhases;
use App\Modules\Project\UseCases\UpdatePhase;
use App\Modules\Project\UseCases\UpdatePhaseStatus;
use App\Modules\Project\ValueObjects\PhaseStatus;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectPhaseController
{
    /** フェーズ一覧 */
    public function index(int $projectId, ListPhases $useCase): AnonymousResourceCollection
    {
        $phases = $useCase->handle($projectId);

        return ProjectPhaseResource::collection($phases);
    }

    /** フェーズ作成 */
    public function store(int $projectId, StorePhaseRequest $request, CreatePhase $useCase): JsonResponse
    {
        $phase = $useCase->handle($projectId, CreatePhaseDTO::fromArray($request->validated()));

        return (new ProjectPhaseResource($phase))
            ->response()
            ->setStatusCode(201);
    }

    /** フェーズ更新 */
    public function update(int $projectId, int $id, UpdatePhaseRequest $request, ProjectPhaseRepository $phaseRepo, UpdatePhase $useCase): JsonResponse
    {
        $phase = $phaseRepo->findByProjectAndId($projectId, $id);
        abort_if($phase === null, 404);

        $updated = $useCase->handle($phase, UpdatePhaseDTO::fromArray($request->validated()));

        return (new ProjectPhaseResource($updated))->response();
    }

    /** フェーズ削除 */
    public function destroy(int $projectId, int $id, ProjectPhaseRepository $phaseRepo, DeletePhase $useCase): JsonResponse
    {
        $phase = $phaseRepo->findByProjectAndId($projectId, $id);
        abort_if($phase === null, 404);

        $useCase->handle($phase);

        return ApiResponse::message('フェーズを削除しました。');
    }

    /** ステータス変更 */
    public function updateStatus(int $projectId, int $id, UpdatePhaseStatusRequest $request, ProjectPhaseRepository $phaseRepo, UpdatePhaseStatus $useCase): JsonResponse
    {
        $phase = $phaseRepo->findByProjectAndId($projectId, $id);
        abort_if($phase === null, 404);

        $updated = $useCase->handle($phase, new PhaseStatus($request->validated()['status']));

        return (new ProjectPhaseResource($updated))->response();
    }

    /** 並び順更新 */
    public function sort(int $projectId, SortPhasesRequest $request, SortPhases $useCase): JsonResponse
    {
        $useCase->handle($projectId, $request->validated()['ids']);

        return ApiResponse::message('並び順を更新しました。');
    }
}
