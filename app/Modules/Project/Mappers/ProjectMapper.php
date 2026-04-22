<?php

namespace App\Modules\Project\Mappers;

use App\Modules\Project\DTO\CreateProjectDTO;
use App\Modules\Project\DTO\UpdateProjectDTO;
use App\Modules\Project\Entities\ProjectEntity;
use App\Modules\Project\ValueObjects\ProjectStatus;

class ProjectMapper
{
    public function toEntity(CreateProjectDTO|UpdateProjectDTO $dto): ProjectEntity
    {
        $status = $dto instanceof CreateProjectDTO
            ? new ProjectStatus($dto->status)
            : new ProjectStatus('not_started'); // UpdateDTO はステータスを別エンドポイントで更新

        return new ProjectEntity(
            name: $dto->name ?? '',
            clientName: $dto->clientName,
            status: $status,
            startDate: $dto->startDate,
            deadline: $dto->deadline,
            estimatedHours: $dto->estimatedHours,
        );
    }
}
