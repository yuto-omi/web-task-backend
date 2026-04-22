## Events and caching

This repo uses:

- **Application events** dispatched from use cases (`event(new SomeEvent(...))`)
- **Listeners** registered in module event providers
- **PublicCache** (`app/Shared/Support/PublicCache.php`) for caching public endpoints

### 1) Where events/listeners live

Per module:

- Events: `app/Modules/{Module}/Events/*`
- Listeners: `app/Modules/{Module}/Listeners/*`
- Event provider: `app/Modules/{Module}/Providers/*EventServiceProvider.php`

Providers are discovered automatically (see `docs/MODULE_PROVIDER_DISCOVERY.md`).

### 2) Typical flow

1. Controller calls a UseCase
2. UseCase performs the action via repository
3. UseCase dispatches an event
4. Listener reacts (e.g. invalidates cache)

### 3) PublicCache strategy

Public endpoints may cache responses using:

- `PublicCache::remember($key, $tags, $ttlSeconds, $callback)`

Invalidation:

- `PublicCache::invalidate($tags)`

Two behaviors depending on the cache driver:

- **Tag-supporting drivers**: flush the tag set
- **No tags support**: keys are versioned by tag using an internal version prefix, so invalidation increments the version

### 4) Cache invalidation examples

Current listeners:

- News: invalidates `['news']`
- NewsCategory: invalidates `['news-categories', 'news']`

If you add a new module with public endpoints, prefer:

- tag public cache entries with a module-specific tag
- invalidate the module tag on create/update/delete events

