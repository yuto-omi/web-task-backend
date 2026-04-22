# Controller / Form Request / API Resource

## Controller

- **薄く保つ**: リクエスト受信・Service 呼び出し・レスポンス返却のみ。
- ビジネスロジックを Controller に書かない。
- 1アクション = 1 public メソッド（`__invoke` または Resource Controller の 7メソッド）。

```php
final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function show(int $id): UserResource
    {
        $user = $this->userService->findById($id);
        return new UserResource($user);
    }
}
```

---

## Form Request（バリデーション）

- バリデーションは必ず Form Request クラスに分離する。Controller に `validate()` を書かない。
- `authorize()` でリソースへの認可チェックも行う。
- カスタムルールは `app/Rules/` に切り出す。

```php
final class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy で制御する場合は $this->user()->can(...) を呼ぶ
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
        ];
    }
}
```

---

## API Resource（レスポンス整形）

- Eloquent モデルを直接 JSON に変換しない。必ず `JsonResource` / `ResourceCollection` を使用する。
- クライアントに不要なフィールド（`password`、`remember_token` など）は含めない。

```php
final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```
