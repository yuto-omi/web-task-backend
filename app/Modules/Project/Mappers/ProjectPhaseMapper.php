<?php

namespace App\Modules\Project\Mappers;

use App\Modules\Project\DTO\CreatePhaseDTO;
use App\Modules\Project\DTO\UpdatePhaseDTO;
use App\Modules\Project\Entities\ProjectPhaseEntity;
use App\Modules\Project\ValueObjects\PhaseStatus;

class ProjectPhaseMapper
{
    public function toEntity(CreatePhaseDTO|UpdatePhaseDTO $dto): ProjectPhaseEntity
    {
        return new ProjectPhaseEntity(
            name: $dto->name ?? '',
            status: new PhaseStatus('not_started'),
            assigneeId: $dto->assigneeId,
            startDate: $dto->startDate,
            endDate: $dto->endDate,
            estimatedHours: $dto->estimatedHours,
            sortOrder: $dto->sortOrder,
            progressRate: $dto->progressRate,
        );
    }
}
