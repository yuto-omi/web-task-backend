# rolesテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| name | VARCHAR(255) | NO | | ロール名（例: admin, editor） |
| guard_name | VARCHAR(255) | NO | | 認証ガード名（`api` 固定） |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

## 備考

- `name` + `guard_name` の組み合わせが一意
- Spatie Permissionが自動管理するテーブル
- 初期データは `RolesAndPermissionsSeeder` で投入
- rolesテーブルにソフトデリートはない（完全削除）