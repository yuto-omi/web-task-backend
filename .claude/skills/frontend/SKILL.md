---
name: frontend-code
description: Next.js (App Router) / React / TypeScript / shadcn/ui でフロントエンド実装・リファクタリングを行うときに使用する。コンポーネント実装、Server Actions、API 呼び出し、フォーム (react-hook-form + zod)、テスト (Jest / RTL / Cypress)、Storybook 追加などフロント関連タスク全般が対象。Windows + WSL2 + Docker 環境前提。
references:
  - references/directory-structure.md
  - references/implementation-guidelines.md
  - references/state-management.md
  - references/api-and-data-fetching.md
  - references/form-implementation.md
  - references/security.md
---

## このスキルが呼び出されたとき

1. タスク内容を確認し、該当する references ファイルを参照する
2. 新機能追加の場合は `references/directory-structure.md` の「機能新規作成フロー」に従って実装する
3. データ取得・更新の実装は `references/api-and-data-fetching.md` の判断フローチャートで方針を決める
4. フォームを実装する場合は `references/form-implementation.md` のパターンをそのまま踏襲する
5. 実装後は `references/implementation-guidelines.md` のテスト・Storybook 追加手順を確認する
6. セキュリティに関わる実装（認証・外部入力・環境変数）は `references/security.md` を必ず参照する

## 技術スタック

| カテゴリ | ライブラリ / ツール |
|---|---|
| フレームワーク | Next.js (App Router), React, TypeScript |
| UI | shadcn/ui, TailwindCSS, lucide-react, cva |
| フォーム | react-hook-form, zod |
| 状態管理 | zustand |
| 認証 | Laravel JWT（httpOnly Cookie） |
| データ取得 | fetch, TanStack Query |
| テスト | Jest, React Testing Library, Cypress |
| ドキュメント | Storybook |
| その他 | react-hot-toast |

## 参照ドキュメント

| ファイル | 内容 |
|---|---|
| `references/directory-structure.md` | ディレクトリ構造・ファイル配置原則・機能新規作成フロー |
| `references/implementation-guidelines.md` | 設計方針・Rendering Strategy・コーディング規約・テスト・ロギング |
| `references/state-management.md` | 状態の種類・zustand の使いどころ・store 設計 |
| `references/api-and-data-fetching.md` | データ取得/更新の使い分け・Server Actions・タイムアウト・キャッシュ |
| `references/form-implementation.md` | react-hook-form + zod + Server Actions の実装パターン |
| `references/security.md` | XSS・CSRF・認証認可・環境変数・依存関係管理 |
