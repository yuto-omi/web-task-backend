<?php

namespace App\Modules\News\Controllers;

use App\Modules\News\DTO\CreateNewsDTO;
use App\Modules\News\DTO\UpdateNewsDTO;
use App\Modules\News\Models\News;
use App\Modules\News\Requests\AdminIndexRequest;
use App\Modules\News\Requests\StoreNewsRequest;
use App\Modules\News\Requests\UpdateNewsRequest;
use App\Modules\News\Resources\NewsResource;
use App\Modules\News\UseCases\CreateNews;
use App\Modules\News\UseCases\DeleteNews;
use App\Modules\News\UseCases\GetAdminNews;
use App\Modules\News\UseCases\ListAdminNews;
use App\Modules\News\UseCases\UpdateNews;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class NewsAdminController
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *   path="/admin/news",
     *   tags={"News"},
     *   summary="List news (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string", maxLength=100)),
     *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"draft","published","archived"})),
     *   @OA\Parameter(name="is_featured", in="query", required=false, @OA\Schema(type="boolean")),
     *   @OA\Parameter(name="category_id", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="sort", in="query", required=false, @OA\Schema(type="string", enum={"created_at","-created_at","published_at","-published_at","title","-title"})),
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
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(
        AdminIndexRequest $request,
        ListAdminNews $useCase
    ): AnonymousResourceCollection {
        $this->authorize('viewAny', News::class);

        $perPage = (int) ($request->validated()['per_page'] ?? 15);
        $items = $useCase->handle($request->validated(), $perPage);

        return NewsResource::collection($items);
    }

    /**
     * @OA\Post(
     *   path="/admin/news",
     *   tags={"News"},
     *   summary="Create news (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       required={"category_id","title","content","status"},
     *
     *       @OA\Property(property="category_id", type="integer", example=10),
     *       @OA\Property(property="title", type="string", maxLength=255, example="My news"),
     *       @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="minha-noticia"),
     *       @OA\Property(property="content", type="string", example="Content..."),
     *       @OA\Property(property="thumbnail_url", type="string", nullable=true, maxLength=2048, example="https://cdn.exemplo.com/capa.jpg"),
     *       @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2026-02-05T12:00:00Z"),
     *       @OA\Property(property="status", type="string", enum={"draft","published","archived"}, example="draft"),
     *       @OA\Property(property="is_featured", type="boolean", nullable=true, example=false)
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/News"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function store(
        StoreNewsRequest $request,
        CreateNews $useCase
    ): JsonResponse {
        $this->authorize('create', News::class);

        $news = $useCase->handle(CreateNewsDTO::fromArray($request->validated()));

        return (new NewsResource($news))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *   path="/admin/news/{id}",
     *   tags={"News"},
     *   summary="Get news (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/News"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(int $id, GetAdminNews $useCase): JsonResponse
    {
        $news = $useCase->handle($id);
        $this->authorize('view', $news);

        return (new NewsResource($news))->response();
    }

    /**
     * @OA\Put(
     *   path="/admin/news/{id}",
     *   tags={"News"},
     *   summary="Update news (admin) - PUT",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="category_id", type="integer", example=10),
     *       @OA\Property(property="title", type="string", maxLength=255, example="My news"),
     *       @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="minha-noticia"),
     *       @OA\Property(property="content", type="string", example="Content..."),
     *       @OA\Property(property="thumbnail_url", type="string", nullable=true, maxLength=2048, example="https://cdn.exemplo.com/capa.jpg"),
     *       @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2026-02-05T12:00:00Z"),
     *       @OA\Property(property="status", type="string", enum={"draft","published","archived"}, example="draft"),
     *       @OA\Property(property="is_featured", type="boolean", nullable=true, example=false)
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/News"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     *
     * @OA\Patch(
     *   path="/admin/news/{id}",
     *   tags={"News"},
     *   summary="Update news (admin) - PATCH",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\RequestBody(required=true, @OA\JsonContent()),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/News"))
     *   )
     * )
     */
    public function update(
        int $id,
        UpdateNewsRequest $request,
        GetAdminNews $getUseCase,
        UpdateNews $updateUseCase
    ): JsonResponse {
        $news = $getUseCase->handle($id);
        $this->authorize('update', $news);

        $payload = array_merge(
            $news->only([
                'category_id',
                'title',
                'slug',
                'content',
                'thumbnail_url',
                'published_at',
                'status',
                'is_featured',
            ]),
            $request->validated()
        );

        $updated = $updateUseCase->handle($news, UpdateNewsDTO::fromArray($payload));

        return (new NewsResource($updated))->response();
    }

    /**
     * @OA\Delete(
     *   path="/admin/news/{id}",
     *   tags={"News"},
     *   summary="Delete news (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=204, description="No Content"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(
        int $id,
        GetAdminNews $getUseCase,
        DeleteNews $deleteUseCase
    ): JsonResponse {
        $news = $getUseCase->handle($id);
        $this->authorize('delete', $news);

        $deleteUseCase->handle($news);

        return response()->json(status: 204);
    }
}
