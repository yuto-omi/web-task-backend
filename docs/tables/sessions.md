# sessionsテーブル

## テーブル定義

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | VARCHAR(255) | NO | | PK・セッションID |
| user_id | BIGINT UNSIGNED | YES | NULL | FK → users.id（未ログインはNULL） |
| ip_address | VARCHAR(45) | YES | NULL | IPアドレス（IPv6対応で45文字） |
| user_agent | TEXT | YES | NULL | ブラウザ情報 |
| payload | LONGTEXT | NO | | セッションデータ（Base64エンコード） |
| last_activity | INT | NO | | 最終アクティビティ（Unixタイムスタンプ） |

## 備考

- Webセッション管理用。API（JWT）とは別
- `last_activity` を使って期限切れセッションを自動削除