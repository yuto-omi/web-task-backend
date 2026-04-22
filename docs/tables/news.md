# newsテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| category_id | BIGINT UNSIGNED | NO | | FK → news_categories.id |
| title | VARCHAR(255) | NO | | タイトル |
| slug | VARCHAR(255) | NO | | URLスラッグ（一意） |
| content | LONGTEXT | NO | | 本文 |
| thumbnail_url | VARCHAR(255) | YES | NULL | サムネイル画像URL |
| published_at | DATETIME | YES | NULL | 公開日時（NULLは未公開） |
| status | VARCHAR(255) | NO | | ステータス |
| is_featured | BOOLEAN | NO | false | 注目記事フラグ |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |
| deleted_at | TIMESTAMP | YES | NULL | 削除日時（ソフトデリート） |

## 備考

- `slug` はURL用の一意な識別子（例: `my-first-news`）
- `status` の値は要確認（例: draft, published, archived など）
- `published_at` が NULL の場合は未公開扱い
- ソフトデリート採用のため、削除しても `deleted_at` に日時が入るだけでレコードは残る
