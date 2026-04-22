# password_reset_tokensテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| email | VARCHAR(255) | NO | | PK・メールアドレス |
| token | VARCHAR(255) | NO | | リセットトークン（ハッシュ済み） |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |

## 備考

- パスワードリセット時に一時的にレコードが作成され、使用後に削除される
- `email` がPKのためユーザー1人につき1レコードのみ保持