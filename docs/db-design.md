# DB設計

テーブル定義の詳細は `docs/tables/` を参照。

## ER図

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email UK
        string password
        string avatar_url
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    projects {
        bigint id PK
        bigint created_by FK
        string name
        string client_name
        enum status
        date start_date
        date deadline
        decimal estimated_hours
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    project_phases {
        bigint id PK
        bigint project_id FK
        string name
        bigint assignee_id FK
        date start_date
        date end_date
        enum status
        decimal estimated_hours
        int sort_order
        timestamp created_at
        timestamp deleted_at
    }

    tasks {
        bigint id PK
        bigint project_id FK
        bigint phase_id FK
        bigint parent_task_id FK
        bigint assignee_id FK
        bigint created_by FK
        string title
        text memo
        enum status
        enum priority
        enum type_tag
        date due_date
        decimal estimated_hours
        int sort_order
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    checklist_items {
        bigint id PK
        bigint task_id FK
        string label
        tinyint is_checked
        int sort_order
        timestamp created_at
    }

    work_logs {
        bigint id PK
        bigint task_id FK
        bigint user_id FK
        timestamp started_at
        timestamp ended_at
        int duration_minutes
        text memo
        timestamp created_at
    }

    project_members {
        bigint project_id FK
        bigint user_id FK
        timestamp created_at
    }

    checklist_templates {
        bigint id PK
        string name
        string phase
        json items
        tinyint is_preset
        timestamp created_at
    }

    users ||--o{ projects : "creates"
    users ||--o{ project_members : "belongs to"
    users ||--o{ project_phases : "assigned to"
    users ||--o{ tasks : "assigned to"
    users ||--o{ tasks : "creates"
    users ||--o{ work_logs : "logs"

    projects ||--o{ project_members : "has"
    projects ||--o{ project_phases : "has"
    projects ||--o{ tasks : "has"

    project_phases ||--o{ tasks : "has"

    tasks ||--o{ tasks : "has subtasks"
    tasks ||--o{ checklist_items : "has"
    tasks ||--o{ work_logs : "has"
```

## 備考

- **個人タスク**: `tasks.project_id = NULL` で判定。専用テーブルは作らない
- **プロジェクト削除**: ソフトデリート（`deleted_at`）= アーカイブ扱い
- **完了済みタスクの削除**: 物理削除（`forceDelete()`）。未完了タスクはソフトデリート推奨
- **工数粒度**: 0.5h単位（`decimal(5,1)`）
- **サブタスク**: `tasks.parent_task_id` による自己参照（1階層のみ）
- **認証**: JWT（tymon/jwt-auth）。アクセストークン60分、リフレッシュトークン30日
