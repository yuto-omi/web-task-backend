# 外部 API 呼び出し / キャッシュ / キュー

## 外部 API 呼び出し

- 外部 API は `Http` ファサード（Laravel HTTP Client）を使用する。
- タイムアウトは必ず設定する（推奨: 10秒）。
- 5xx / ネットワークエラーは最大3回リトライ（指数バックオフ）する。

```php
$response = Http::timeout(10)
    ->retry(3, 1000, fn ($e) => $e instanceof ConnectionException)
    ->withToken($token)
    ->post($url, $payload);

if ($response->failed()) {
    Log::error('外部 API エラー', ['status' => $response->status()]);
    throw new ExternalApiException('外部 API の呼び出しに失敗しました');
}
```

### レスポンスのバリデーション

- 外部 API のレスポンスは型チェック・必須キーの存在確認を行う。
- 予期しない構造の場合はカスタム例外をスローする。

---

## キャッシュ

- **原則**: 頻繁に参照され、変更頻度が低いデータをキャッシュする。
- **ドライバー**: Redis を使用する。
- **TTL**: データの性質に応じて設定する（例: マスターデータ = 1日、ユーザー情報 = 5分）。
- **キー命名**: `{ドメイン}:{識別子}` 形式（例: `user:42`、`news:list`）。
- キャッシュ更新時は `Cache::forget()` / `Cache::tags()->flush()` を忘れずに呼ぶ。

```php
$user = Cache::remember("user:{$id}", now()->addMinutes(5), fn () =>
    $this->userRepository->findById($id)
);
```

---

## キュー（非同期処理）

- メール送信・重い処理・外部 API 呼び出しはキューに委譲する。
- Job クラスは `app/Jobs/` に配置し、`ShouldQueue` を実装する。
- 失敗時のリトライ回数・バックオフは Job クラスで明示的に設定する。

```php
final class SendWelcomeEmailJob implements ShouldQueue
{
    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly User $user,
    ) {}

    public function handle(Mailer $mailer): void
    {
        $mailer->to($this->user)->send(new WelcomeEmail($this->user));
    }
}
```
