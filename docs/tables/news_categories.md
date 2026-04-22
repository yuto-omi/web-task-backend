# news_categoriesテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| name | VARCHAR(255) | NO | | カテゴリ名 |
| slug | VARCHAR(255) | NO | | URLスラッグ（一意） |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |
| deleted_at | TIMESTAMP | YES | NULL | 削除日時（ソフトデリート） |

## 備考

- `slug` はURL用の一意な識別子（例: `technology`, `sports`）
- ソフトデリート採用のため削除してもレコードは残る
- newsテーブルから `category_id` で参照される
