# API設計

ベースURL: `http://localhost:8000/api`

詳細仕様（リクエスト・レスポンス）はSwaggerを参照: `http://localhost:8000/api/documentation`

---

## 認証 `/auth`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| POST | `/api/auth/login` | 不要 | ログイン |
| POST | `/api/auth/logout` | 必要 | ログアウト |
| POST | `/api/auth/refresh` | 必要 | トークンリフレッシュ |
| GET | `/api/auth/me` | 必要 | 認証ユーザー情報取得 |
| POST | `/api/auth/password/reset` | 不要 | パスワードリセットメール送信 |
| PUT | `/api/auth/password/reset` | 不要 | パスワードリセット実行 |

---

## プロジェクト `/projects`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/projects` | 必要 | プロジェクト一覧 |
| POST | `/api/projects` | 必要 | プロジェクト作成 |
| GET | `/api/projects/{id}` | 必要 | プロジェクト詳細 |
| PUT | `/api/projects/{id}` | 必要 | プロジェクト更新 |
| DELETE | `/api/projects/{id}` | 必要 | プロジェクト削除（ソフトデリート） |
| PATCH | `/api/projects/{id}/status` | 必要 | ステータス変更 |

---

## フェーズ `/projects/{projectId}/phases`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/projects/{projectId}/phases` | 必要 | フェーズ一覧 |
| POST | `/api/projects/{projectId}/phases` | 必要 | フェーズ作成 |
| PUT | `/api/projects/{projectId}/phases/{id}` | 必要 | フェーズ更新 |
| DELETE | `/api/projects/{projectId}/phases/{id}` | 必要 | フェーズ削除 |
| PATCH | `/api/projects/{projectId}/phases/{id}/status` | 必要 | ステータス変更 |
| PUT | `/api/projects/{projectId}/phases/sort` | 必要 | 並び順更新 |

---

## タスク `/tasks`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/tasks` | 必要 | タスク一覧（クエリ: project_id, phase_id, assignee_id 等でフィルタ） |
| POST | `/api/tasks` | 必要 | タスク作成 |
| GET | `/api/tasks/{id}` | 必要 | タスク詳細 |
| PUT | `/api/tasks/{id}` | 必要 | タスク更新 |
| DELETE | `/api/tasks/{id}` | 必要 | タスク削除（未完了:ソフトデリート / 完了:物理削除） |
| PATCH | `/api/tasks/{id}/status` | 必要 | ステータス変更 |
| PUT | `/api/tasks/sort` | 必要 | 並び順更新 |

---

## チェックリスト `/tasks/{taskId}/checklist`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/tasks/{taskId}/checklist` | 必要 | チェックリスト取得 |
| POST | `/api/tasks/{taskId}/checklist` | 必要 | アイテム追加 |
| PUT | `/api/tasks/{taskId}/checklist/{id}` | 必要 | アイテム更新 |
| DELETE | `/api/tasks/{taskId}/checklist/{id}` | 必要 | アイテム削除 |
| PATCH | `/api/tasks/{taskId}/checklist/{id}/check` | 必要 | チェック切り替え |
| PUT | `/api/tasks/{taskId}/checklist/sort` | 必要 | 並び順更新 |

---

## 作業ログ（工数） `/tasks/{taskId}/work-logs`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/tasks/{taskId}/work-logs` | 必要 | 作業ログ一覧 |
| POST | `/api/tasks/{taskId}/work-logs/start` | 必要 | 作業開始 |
| PATCH | `/api/tasks/{taskId}/work-logs/{id}/stop` | 必要 | 作業終了 |
| POST | `/api/tasks/{taskId}/work-logs` | 必要 | 手動入力 |
| DELETE | `/api/tasks/{taskId}/work-logs/{id}` | 必要 | 削除 |

---

## プロジェクトメンバー `/projects/{projectId}/members`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/projects/{projectId}/members` | 必要 | メンバー一覧 |
| POST | `/api/projects/{projectId}/members/invite` | 必要 | メンバー招待（SendGrid） |
| DELETE | `/api/projects/{projectId}/members/{userId}` | 必要 | メンバー削除 |
| POST | `/api/invite/{token}/accept` | 不要 | 招待受諾・ユーザー登録 |

---

## チェックリストテンプレート `/checklist-templates`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/checklist-templates` | 必要 | テンプレート一覧 |
| POST | `/api/checklist-templates` | 必要 | テンプレート作成 |
| PUT | `/api/checklist-templates/{id}` | 必要 | テンプレート更新 |
| DELETE | `/api/checklist-templates/{id}` | 必要 | テンプレート削除 |
| POST | `/api/tasks/{taskId}/checklist/apply-template` | 必要 | テンプレート適用 |

---

## スケジュール（ガントチャート） `/schedule`

| メソッド | パス | 認証 | 説明 |
|---|---|---|---|
| GET | `/api/schedule` | 必要 | 全プロジェクトのフェーズ一覧（ガントチャート用） |

---

## 備考

- 認証: JWT Bearer Token（`Authorization: Bearer {token}`）
- 全エンドポイント認証必要（招待受諾・ログイン系を除く）
- ソフトデリートされたプロジェクトはデフォルトで除外。`?with_archived=true` で含める
