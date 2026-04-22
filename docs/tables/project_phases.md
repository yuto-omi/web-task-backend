# project_phasesテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| project_id | BIGINT UNSIGNED | NO | | FK → projects.id |
| assignee_id | BIGINT UNSIGNED | YES | NULL | 担当者（FK → users.id） |
| name | VARCHAR(255) | NO | | フェーズ名 |
| status | ENUM | NO | 'not_started' | not_started / in_progress / completed |
| start_date | DATE | YES | NULL | 開始日 |
| end_date | DATE | YES | NULL | 終了日 |
| estimated_hours | DECIMAL(5,1) | YES | NULL | 見積工数（0.5h単位） |
| sort_order | INT | NO | 0 | 表示順 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| deleted_at | TIMESTAMP | YES | NULL | 削除日時（ソフトデリート） |

## リレーション

| テーブル | 種別 | 説明 |
|---|---|---|
| projects | BelongsTo | 所属プロジェクト |
| users | BelongsTo | 担当者 |
| tasks | HasMany | タスク一覧 |

## ステータス定義

| 値 | 表示名 |
|---|---|
| `not_started` | 未着手 |
| `in_progress` | 進行中 |
| `completed` | 完了 |

## フェーズ例

| フェーズ名 |
|---|
| 要件定義 |
| ワイヤーフレーム |
| デザイン |
| コーディング |
| テスト |
| 納品 |

## 備考

- ガントチャート表示用に `start_date` / `end_date` / `sort_order` を使用
- 削除はソフトデリート
