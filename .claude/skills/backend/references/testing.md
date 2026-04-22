# テスト

| レイヤー | ツール | 対象 |
|---|---|---|
| Feature テスト | PHPUnit / Pest | HTTP リクエスト〜レスポンスの結合テスト |
| Unit テスト | PHPUnit / Pest | Service / Repository / ドメインロジックの単体テスト |

## テスト方針

- **Feature テスト優先**: API エンドポイントは必ず Feature テストを書く。
- **DB**: `RefreshDatabase` トレイトを使用し、テストごとにロールバックする。
- **外部依存**: HTTP Client / メール / キューは `Http::fake()` / `Mail::fake()` / `Queue::fake()` でモックする。
- **Factory 必須**: テストデータは Factory で生成する。ハードコードしない。

```php
it('ユーザーを取得できる', function () {
    $user = User::factory()->create();

    $this->getJson("/api/users/{$user->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $user->id);
});
```

## 認証が必要なエンドポイントのテスト

JWT 認証を使用しているため、テスト時は `actingAs()` またはトークンを直接ヘッダーに付与する。

```php
it('認証済みユーザーのみアクセスできる', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'api')
        ->getJson('/api/me')
        ->assertOk();
});
```
