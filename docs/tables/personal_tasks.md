# 個人タスク

## 概要

個人タスクは専用テーブルを持たず、`tasks` テーブルの `project_id = NULL` で識別する。

```
project_id = NULL  → 個人タスク（マイページで管理）
project_id = {id}  → プロジェクトタスク
```

---

## 関連テーブル

### tasks（個人タスクとして使用するカラム）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| project_id | BIGINT UNSIGNED | YES | NULL | **NULLのとき個人タスク** |
| phase_id | BIGINT UNSIGNED | YES | NULL | 個人タスクでは常にNULL |
| parent_task_id | BIGINT UNSIGNED | YES | NULL | サブタスクの親（1階層のみ） |
| assignee_id | BIGINT UNSIGNED | NO | | 担当者（個人タスクは作成者自身） |
| created_by | BIGINT UNSIGNED | NO | | 作成者 |
| title | VARCHAR(255) | NO | | タスク名 |
| memo | TEXT | YES | NULL | メモ |
| status | ENUM | NO | 'pending' | 未着手 / 進行中 / レビュー待ち / 完了 |
| priority | ENUM | YES | NULL | 低 / 中 / 高 |
| type_tag | ENUM | YES | NULL | 種別タグ |
| due_date | DATE | YES | NULL | 期日 |
| estimated_hours | DECIMAL(5,1) | YES | NULL | 見積工数（0.5h単位） |
| sort_order | INT | NO | 0 | 表示順 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |
| deleted_at | TIMESTAMP | YES | NULL | 削除日時（ソフトデリート） |

### checklist_items（tasksに紐づく）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| task_id | BIGINT UNSIGNED | NO | | FK → tasks.id |
| label | VARCHAR(255) | NO | | チェック項目名 |
| is_checked | TINYINT(1) | NO | 0 | チェック状態 |
| sort_order | INT | NO | 0 | 表示順 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |

---

## ステータス定義

| 値 | 表示名 | 説明 |
|---|---|---|
| `pending` | 未着手 | デフォルト |
| `in_progress` | 進行中 | 着手済み |
| `in_review` | レビュー待ち | 確認待ち |
| `done` | 完了 | チェックボックスONで自動遷移 |

---

## 取得クエリ例

```sql
-- 自分の個人タスク一覧（未完了）
SELECT * FROM tasks
WHERE assignee_id = {user_id}
  AND project_id IS NULL
  AND status != 'done'
  AND deleted_at IS NULL
ORDER BY due_date ASC, sort_order ASC;

-- 今日期限の個人タスク
SELECT * FROM tasks
WHERE assignee_id = {user_id}
  AND project_id IS NULL
  AND due_date = CURDATE()
  AND deleted_at IS NULL;
```

---

## 備考

- 個人タスクの閲覧・編集は **作成者本人のみ**（他メンバーからは見えない）
- 完了タスクの削除は物理削除（`forceDelete()`）
- 未完了タスクの削除はソフトデリート（`deleted_at`）
- サブタスクは1階層のみ（`parent_task_id` による自己参照）
- マイページ（`/my`）で今日・今週の切り替え表示
