# model_has_rolesテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| role_id | BIGINT UNSIGNED | NO | | FK → roles.id（CASCADE DELETE） |
| model_type | VARCHAR(255) | NO | | モデルのクラス名（例: `App\Modules\User\Models\User`） |
| model_id | BIGINT UNSIGNED | NO | | モデルのID |

## 備考

- ユーザー（またはその他モデル）とロールを紐付ける中間テーブル
- `model_type` + `model_id` のポリモーフィック構成により任意のモデルにロール付与が可能
- `role_id` + `model_id` + `model_type` の複合PK
- ロールが削除されると関連レコードも自動削除（CASCADE）
- timestamps・softDeletesなし