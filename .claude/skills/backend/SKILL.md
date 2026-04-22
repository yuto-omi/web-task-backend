---
name: backend-code
description: Laravel 11.x / PHP 8.3 でバックエンド実装・リファクタリングを行うときに使用する。REST API エンドポイント設計、Controller / Form Request / API Resource の実装、DDD に基づく Domain・Service・Repository パターン、Eloquent モデルとマイグレーション、JWT 認証・認可 (Policy)、Redis キャッシュ、Laravel Queue による非同期処理、外部 API 連携 (Http ファサード)、PHPUnit / Pest でのテスト実装などバックエンド関連タスク全般が対象。MySQL 8.0+、Docker 開発環境前提。
references:
  - references/directory-structure.md
  - references/implementation-guidelines.md
  - references/controller-form-request-resource.md
  - references/repository-pattern.md
  - references/api-cache-queue.md
  - references/testing.md
  - references/security.md
  - references/logging-migration.md
---

## このスキルが呼び出されたとき

1. タスク内容を確認し、該当する references ファイルを参照する
2. 新機能追加の場合は `references/directory-structure.md` のディレクトリ構造に従って実装する
3. Controller 実装は `references/controller-form-request-resource.md` のパターンを踏襲する
4. ドメインロジックは `references/repository-pattern.md` に従い Service / Repository に委譲する
5. 認証・認可・CORS に関わる実装は `references/security.md` を必ず参照する
6. 実装後は `references/testing.md` に従い Feature テストを追加する

## 技術スタック

| カテゴリ | ライブラリ / ツール |
|---|---|
| フレームワーク | Laravel 11.x, PHP 8.3 |
| 認証 | JWT（tymon/jwt-auth 2.x） |
| バリデーション | Form Request, Rule クラス |
| ORM | Eloquent ORM |
| DB | MySQL 8.0+ |
| キャッシュ | Redis（Laravel Cache） |
| キュー | Laravel Queue（Redis ドライバー） |
| テスト | PHPUnit, Pest |
| API仕様 | RESTful JSON API |
| インフラ | Docker（開発）, Nginx + PHP-FPM（本番） |

## 参照ドキュメント

| ファイル | 内容 |
|---|---|
| `references/directory-structure.md` | ディレクトリ構造・ファイル配置原則 |
| `references/implementation-guidelines.md` | 設計方針・レスポンス設計・コーディング規約 |
| `references/controller-form-request-resource.md` | Controller / Form Request / API Resource の実装パターン |
| `references/repository-pattern.md` | Repository パターン・Service 層の設計 |
| `references/api-cache-queue.md` | 外部 API 呼び出し・キャッシュ・キュー |
| `references/testing.md` | テスト方針・PHPUnit / Pest の実装パターン |
| `references/security.md` | JWT 認証・認可・CORS・レート制限・環境変数 |
| `references/logging-migration.md` | ロギング・マイグレーション |
