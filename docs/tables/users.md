# usersテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| name | VARCHAR(255) | NO | | 名前 |
| email | VARCHAR(255) | NO | | メールアドレス（一意） |
| email_verified_at | TIMESTAMP | YES | NULL | メール認証日時 |
| password | VARCHAR(255) | NO | | パスワード（ハッシュ済み） |
| remember_token | VARCHAR(100) | YES | NULL | ログイン維持トークン |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

## 備考

- 認証はJWT（tymon/jwt-auth）を使用
- `guard_name` は `api`
- ロール管理はSpatieのHasRolesトレイトで行う