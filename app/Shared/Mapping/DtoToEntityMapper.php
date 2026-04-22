<?php

namespace App\Shared\Mapping;

/**
 * @template TDto of object
 * @template TEntity of object
 */
interface DtoToEntityMapper
{
    /**
     * @param  TDto  $dto
     * @return TEntity
     */
    public function toEntity(object $dto): object;
}
