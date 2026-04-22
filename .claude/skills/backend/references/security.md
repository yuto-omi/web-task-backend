# セキュリティ

## 認証・認可

- API 認証は **JWT（tymon/jwt-auth）** を使用する。
- アクセストークン有効期限: 60分 / リフレッシュトークン有効期限: 30日。
- 認可は **Policy** クラスで一元管理する。Controller / Service に認可ロジックを書かない。
- ルートは `auth:api` ミドルウェアで保護する。

```php
// routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class);
});
```

---

## SQL インジェクション対策

- クエリは必ず **Eloquent ORM** または **クエリビルダーのバインディング** を使用する。
- 生クエリ（`DB::statement` / `whereRaw`）に動的値を直接埋め込まない。

---

## CORS

- `config/cors.php` で許可 Origin を明示的に設定する（Next.js ドメインのみ）。
- `'*'` はローカル開発のみ許容する。

---

## レート制限

- 認証エンドポイントには `throttle:login` を設定する。
- API 全体にも適切なレート制限ミドルウェアを適用する。

```php
Route::middleware('throttle:60,1')->group(function () {
    // ...
});
```

---

## 環境変数

- シークレット（DB 接続情報・API キー・JWT シークレット）は `.env` で管理し、コードに直接記述しない。
- `config/` 経由でのみ参照し、アプリケーションコードで `env()` を直接呼ばない。

---

## エラーハンドリング

- `bootstrap/app.php` の `withExceptions()` でグローバルな例外ハンドラーを定義する。
- ユーザー向けレスポンスにスタックトレース・内部情報を含めない（本番環境）。
- カスタム例外は `app/Exceptions/` に定義し、ドメインエラーを表現する。

```php
$exceptions->render(function (ModelNotFoundException $e, Request $request) {
    return response()->json(['message' => 'リソースが見つかりません'], 404);
});
```
