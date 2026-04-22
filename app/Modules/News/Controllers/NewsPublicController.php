<?php

namespace App\Modules\News\Controllers;

use App\Modules\News\Requests\PublicIndexRequest;
use App\Modules\News\Resources\NewsResource;
use App\Modules\News\UseCases\GetPublicNews;
use App\Modules\News\UseCases\ListPublicNews;
use App\Shared\Support\PublicCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class NewsPublicController
{
    /**
     * @OA\Get(
     *   path="/public/news",
     *   tags={"News"},
     *   summary="List public news",
     *
     *   @OA\Parameter(name="category", in="query", required=false, @OA\Schema(type="string", maxLength=255)),
     *   @OA\Parameter(name="q", in="query", required=false, @OA\Schema(type="string", maxLength=255)),
     *   @OA\Parameter(name="featured", in="query", required=false, @OA\Schema(type="boolean")),
     *   @OA\Parameter(
     *     name="sort",
     *     in="query",
     *     required=false,
     *
     *     @OA\Schema(type="string", enum={"published_at","-published_at","created_at","-created_at","title","-title"})
     *   ),
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
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/News")),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta", type="object")
     *     )
     *   )
     * )
     */
    public function index(
        PublicIndexRequest $request,
        ListPublicNews $useCase
    ): AnonymousResourceCollection {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);

        ksort($validated);
        $cacheKey = 'public.news.index.'.md5(http_build_query($validated));
        $ttl = (int) config('cache.public_ttl', 300);

        $items = PublicCache::remember(
            $cacheKey,
            ['news'],
            $ttl,
            fn () => $useCase->handle($validated, $perPage)
        );

        return NewsResource::collection($items);
    }

    /**
     * @OA\Get(
     *   path="/public/news/{slug}",
     *   tags={"News"},
     *   summary="Get public news by slug",
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
     *       @OA\Property(property="data", ref="#/components/schemas/News")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(string $slug, GetPublicNews $useCase): JsonResponse
    {
        $cacheKey = 'public.news.show.'.$slug;
        $ttl = (int) config('cache.public_ttl', 300);

        $news = PublicCache::remember(
            $cacheKey,
            ['news'],
            $ttl,
            fn () => $useCase->handle($slug)
        );

        return (new NewsResource($news))->response();
    }
}
