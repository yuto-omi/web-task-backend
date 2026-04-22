# Repository パターン

- Eloquent への直接依存を Domain 層から分離するために Repository を使用する。
- Interface を `Domain/` に定義し、実装を `Infrastructure/` に置く。
- `AppServiceProvider` でインターフェースと実装をバインドする。

```php
// Domain/User/Repositories/UserRepositoryInterface.php
interface UserRepositoryInterface
{
    public function findById(int $id): User;
    public function save(User $user): void;
}

// Infrastructure/Repositories/EloquentUserRepository.php
final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function save(User $user): void
    {
        $user->save();
    }
}

// Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
}
```

## Service からの利用

```php
final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function findById(int $id): User
    {
        return $this->userRepository->findById($id);
    }
}
```
