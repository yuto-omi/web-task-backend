# 実装方針・コーディング規約

## 設計

- **単一責任**: Controller の責務はリクエスト受信・Service 呼び出し・レスポンス返却のみ。ビジネスロジックを Controller に書かない。
- **DDD**: `Domain/` 配下はドメイン単位で分割する。Repository パターンで DB 依存を抽象化する。
- **型設計**: PHP 8.3 の型宣言（プロパティ型・返り値型・引数型）を必ず付与する。`mixed` 型は禁止。

---

## レスポンス設計

- **一貫したフォーマット**: すべての API レスポンスは `JsonResource` / `ResourceCollection` を通じて統一する。
- **ステータスコード**: 適切な HTTP ステータスコードを返す（201 Created、422 Unprocessable Entity など）。
- **エラーレスポンス**: `errors` キーに詳細を含めた統一フォーマットで返す。

```json
// 成功
{ "data": { ... }, "message": "成功しました" }

// バリデーションエラー (422)
{ "message": "入力値が不正です", "errors": { "email": ["メールアドレスの形式が正しくありません"] } }

// サーバーエラー (500)
{ "message": "サーバーエラーが発生しました" }
```

---

## コーディング規約

- **命名規則**:
  | 対象 | 規則 | 例 |
  |---|---|---|
  | クラス | `PascalCase` | `UserService` |
  | メソッド / 変数 | `camelCase` | `findById` |
  | DB カラム | `snake_case` | `created_at` |
  | 定数 | `UPPER_SNAKE_CASE` | `MAX_RETRY_COUNT` |
- **Magic string / number 禁止**: `const` や `enum` として定義する。
- **1メソッド 30行以内**: 超える場合はメソッド分割を検討する。
- **`final` クラス優先**: 継承を意図しないクラスには `final` を付与する。

---

## インポート順序

1. PHP 標準クラス（`DateTime` など）
2. Laravel コアクラス
3. 外部パッケージ
4. アプリケーション内クラス（アルファベット順）
