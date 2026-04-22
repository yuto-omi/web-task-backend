# projectsテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| created_by | BIGINT UNSIGNED | NO | | 作成者（FK → users.id） |
| name | VARCHAR(255) | NO | | プロジェクト名 |
| client_name | VARCHAR(255) | YES | NULL | クライアント名 |
| status | ENUM | NO | 'not_started' | not_started / in_progress / completed |
| start_date | DATE | YES | NULL | 開始日 |
| deadline | DATE | YES | NULL | 納品日 |
| estimated_hours | DECIMAL(5,1) | YES | NULL | 見積工数（0.5h単位） |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |
| deleted_at | TIMESTAMP | YES | NULL | 削除日時（ソフトデリート） |

## リレーション

| テーブル | 種別 | 説明 |
|---|---|---|
| users | BelongsTo | 作成者 |
| project_members | HasMany | 参加メンバー |
| project_phases | HasMany | フェーズ一覧 |
| tasks | HasMany | タスク一覧 |

## ステータス定義

| 値 | 表示名 |
|---|---|
| `not_started` | 未着手 |
| `in_progress` | 進行中 |
| `completed` | 完了 |

## 備考

- 削除はソフトデリート（`deleted_at`）= アーカイブ扱い
- 進捗率はタスクの完了数 ÷ 全タスク数で自動算出
