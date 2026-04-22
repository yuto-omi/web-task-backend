<?php

namespace App\Modules\NewsCategory\Controllers;

use App\Modules\NewsCategory\Requests\PublicIndexRequest;
use App\Modules\NewsCategory\Resources\NewsCategoryResource;
use App\Modules\NewsCategory\UseCases\GetPublicNewsCategory;
use App\Modules\NewsCategory\UseCases\ListPublicNewsCategories;
use App\Shared\Support\PublicCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class NewsCategoryPublicController
{
    /**
     * @OA\Get(
     *   path="/public/news-categories",
     *   tags={"News Categories"},
     *   summary="List public categories",
     *
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", minimum=1, maximum=100)),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NewsCategory")),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta", type="object")
     *     )
     *   )
     * )
     */
    public function index(
        PublicIndexRequest $request,
        ListPublicNewsCategories $useCase
    ): AnonymousResourceCollection {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);

        ksort($validated);
        $cacheKey = 'public.news_categories.index.'.md5(http_build_query($validated));
        $ttl = (int) config('cache.public_ttl', 300);

        $items = PublicCache::remember(
            $cacheKey,
            ['news-categories'],
            $ttl,
            fn () => $useCase->handle($validated, $perPage)
        );

        return NewsCategoryResource::collection($items);
    }

    /**
     * @OA\Get(
     *   path="/public/news-categories/{slug}",
     *   tags={"News Categories"},
     *   summary="Get public category by slug",
     *
     *   @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="data", ref="#/components/schemas/NewsCategory")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(string $slug, GetPublicNewsCategory $useCase): JsonResponse
    {
        $cacheKey = 'public.news_categories.show.'.$slug;
        $ttl = (int) config('cache.public_ttl', 300);

        $category = PublicCache::remember(
            $cacheKey,
            ['news-categories'],
            $ttl,
            fn () => $useCase->handle($slug)
        );

        return (new NewsCategoryResource($category))->response();
    }
}
