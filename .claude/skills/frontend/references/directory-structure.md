# ディレクトリ構造

```
frontend/
├── app/                          # ルーティング専用 (page, layout, loading, error)
│   ├── (auth)/
│   │   ├── login/
│   │   └── register/
│   ├── (authenticated)/
│   │   ├── tasks/
│   │   ├── projects/
│   │   └── layout.tsx
│   ├── api/                      # Route Handlers (必要な場合のみ)
│   ├── layout.tsx
│   └── page.tsx
│
├── components/
│   ├── ui/                       # shadcn/ui (自動生成先)
│   └── shared/                   # プロジェクト横断の自作共通コンポーネント
│       ├── header.tsx
│       ├── header.test.tsx
│       └── header.stories.tsx
│
├── features/                     # ドメインごとの機能 (コロケーション)
│   └── task/
│       ├── schemas/
│       │   └── task.schema.ts    # zod schema + 型定義
│       ├── api/
│       │   ├── get-tasks.ts
│       │   └── create-task.ts
│       ├── components/
│       │   ├── task-list.tsx
│       │   ├── task-list.test.tsx
│       │   ├── task-list.stories.tsx
│       │   ├── task-card.tsx
│       │   └── task-form.tsx
│       ├── hooks/
│       │   └── use-task-filter.ts
│       └── stores/
│           └── use-task-ui-store.ts
│
├── hooks/                        # 横断的なカスタムフックのみ
│   └── use-media-query.ts
│
├── lib/                          # 横断的なユーティリティ / クライアント
│   ├── api/
│   │   └── api-fetch.ts          # 共通 fetch ラッパー
│   ├── auth.ts                   # Cookie から JWT を取得するユーティリティ
│   └── utils.ts
│
├── providers/                    # Context Providers
│   └── query-provider.tsx
│
├── stores/                       # 横断的な zustand store
│   └── use-sidebar-store.ts
│
├── styles/
│   └── globals.css
│
├── types/                        # グローバル型拡張のみ
│   ├── global.d.ts
│   └── global.d.ts
│
├── tests/
│   └── e2e/                      # Cypress (E2E のみ独立)
│
└── public/
```

## ファイル配置の原則

- **コロケーション優先**: テスト (`.test.tsx`) と Storybook (`.stories.tsx`) はコンポーネントと同じディレクトリに配置する。E2E テストのみ `tests/e2e/` に独立させる。
- **ドメイン固有は `features/{domain}/` に集約**: コンポーネント・フック・store・schema・API 層すべてをドメインディレクトリ内に置く。ルート直下の `hooks/` `lib/` `stores/` は**横断的なもの専用**。
- **共通コンポーネントは `components/` 直下**: `components/ui/` は shadcn/ui の自動生成先、`components/shared/` はプロジェクト横断の自作コンポーネント。`app/` 配下にコンポーネントを置かない。
- **型定義はコロケーション**: zod schema からの `z.infer` を基本とし、`features/{domain}/schemas/` に配置する。同一ドメインの入力・レスポンス型は1ファイルにまとめる（例: `login.schema.ts` に `LoginInput` と `LoginResponse` を共存）。ルート直下の `types/` は `global.d.ts` など環境拡張のみに限定する。
- **`app/` はルーティング専用**: `page.tsx` / `layout.tsx` / `loading.tsx` / `error.tsx` / `not-found.tsx` / Route Groups `(xxx)` のみを配置する。ビジネスロジックは `features/` に置く。
- **バレルファイル（`index.ts`）禁止**: `'use server'` と `'use client'` が混在する再エクスポートは Next.js App Router でエラーになる。また tree-shaking が効かずバンドルが肥大化するため、import パスは各ファイルへの直接参照を使う。
- **API ファイルの命名は操作名ベース**: HTTP メソッド名をプレフィックスにしない。`post-login.ts` ではなく `login.ts`、`get-tasks.ts`・`create-task.ts` のように「何をするか」で命名する。

---

## 機能新規作成フロー

`features/{domain}/` 配下に新しい機能を追加する場合、以下の順序で作業する。

1. **型定義 & zod schema 作成** — `features/{domain}/schemas/` に zod schema を定義し、`z.infer<typeof schema>` で型を導出する。バックエンドの API レスポンス構造と一致させる。
2. **API 層の実装** — `features/{domain}/api/` に Server Actions または fetch ラッパーを配置する。Server Actions を第一選択とする。
3. **Server Component の実装** — `features/{domain}/components/` に一覧・詳細などの Server Component を作成する。データ取得は Server Component 内で完結させる。
4. **Client Component の切り出し** — インタラクション (フォーム・モーダル・DnD など) が必要な部分のみ `"use client"` を付与して最小単位で切り出す。Server Component から props 経由でデータを受け取る。
5. **ページへの組み込み** — `app/(routes)/{path}/page.tsx` から features 配下のコンポーネントを呼び出す。`page.tsx` は薄く保ち、ロジックは置かない。
6. **Storybook 追加** — 主要コンポーネントのストーリーを追加する。Props の主要パターン (正常・ローディング・空・エラー) を網羅する。
7. **ユニットテスト追加** — Jest + RTL でテストを追加する。ユーザーインタラクションは `@testing-library/user-event` を使用する。
8. **E2E テスト追加 (重要フローのみ)** — タスク作成・編集・削除など業務上クリティカルな操作は `tests/e2e/` に Cypress シナリオを追加する。

### ディレクトリ配置例

```
features/
└── task/
    ├── schemas/
    │   └── task.schema.ts      # zod schema + 型
    ├── api/
    │   ├── get-tasks.ts        # Server Actions (取得)
    │   └── create-task.ts      # Server Actions (作成)
    ├── components/
    │   ├── task-list.tsx       # Server Component
    │   ├── task-card.tsx       # Server Component
    │   └── task-form.tsx       # Client Component
    ├── hooks/
    │   └── use-task-filter.ts  # 必要な場合のみ
    └── stores/
        └── use-task-ui-store.ts # 必要な場合のみ
```

### 新規 page.tsx のひな形

```tsx
// app/(authenticated)/tasks/page.tsx
import { Suspense } from 'react';
import { TaskList } from '@/features/task/components/task-list';
import { TaskListSkeleton } from '@/features/task/components/task-list-skeleton';

export default function TasksPage() {
  return (
    <Suspense fallback={<TaskListSkeleton />}>
      <TaskList />
    </Suspense>
  );
}
```

### UI 状態の取り扱い

| 状態 | 実装方法 |
|---|---|
| ローディング | `loading.tsx` または `<Suspense>` + Skeleton コンポーネント |
| エラー | `error.tsx` (ルートセグメント単位) / `try-catch` + toast |
| 空状態 | `{items.length === 0 ? <EmptyState /> : <List />}` を Server Component で処理 |
| 権限なし | Server Actions / Server Component 内で判定し `notFound()` or `redirect()` |
