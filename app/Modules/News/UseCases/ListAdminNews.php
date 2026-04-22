<?php

namespace App\Modules\News\UseCases;

use App\Modules\News\Contracts\NewsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListAdminNews
{
    public function __construct(private readonly NewsRepository $news) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function handle(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->news->paginateAdmin($filters, $perPage);
    }
}
