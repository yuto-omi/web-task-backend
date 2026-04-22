# ディレクトリ構造

```
backend/
├── app/
│   ├── Console/              # Artisan コマンド
│   ├── Domain/               # ドメインロジック (DDD)
│   │   ├── {DomainName}/
│   │   │   ├── Models/       # Eloquent モデル
│   │   │   ├── Repositories/ # リポジトリインターフェース
│   │   │   └── Services/     # ドメインサービス
│   ├── Exceptions/           # カスタム例外 / 例外ハンドラー
│   ├── Http/
│   │   ├── Controllers/      # コントローラー（薄く保つ）
│   │   ├── Middleware/       # ミドルウェア
│   │   ├── Requests/         # Form Request（バリデーション）
│   │   └── Resources/        # API Resource（レスポンス整形）
│   ├── Infrastructure/
│   │   └── Repositories/     # リポジトリ実装
│   └── Providers/            # サービスプロバイダー
├── config/                   # 設定ファイル
├── database/
│   ├── factories/            # テスト用ファクトリー
│   ├── migrations/           # マイグレーション
│   └── seeders/              # シーダー
├── routes/
│   ├── api.php               # API ルート
│   └── web.php               # Web ルート（必要な場合のみ）
└── tests/
    ├── Feature/              # Feature テスト（HTTP レベル）
    └── Unit/                 # Unit テスト
```

## ファイル配置の原則

- **Controller は薄く保つ**: ビジネスロジックは `Domain/` の Service に委譲する。
- **ドメイン単位で分割**: `Domain/{DomainName}/` 配下にモデル・リポジトリインターフェース・サービスを集約する。
- **Repository パターン**: Interface を `Domain/` に定義し、Eloquent 実装を `Infrastructure/` に置く。
- **ルートは `routes/api.php` に集約**: Web ルートは原則使用しない。
