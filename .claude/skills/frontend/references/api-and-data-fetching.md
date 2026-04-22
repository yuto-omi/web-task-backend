# API 呼び出し・データフェッチ

## 基本方針

- データ取得には `fetch` を使用する。
- エンドポイント URL は定数として管理する。
- 認証が必要な場合は `cookies()` から `auth_token`（Laravel JWT）を取得し `Authorization: Bearer` ヘッダーに付与して送信する。

## データ取得・更新の使い分け

「**サーバー第一・必要なときだけクライアント**」を原則とする。TanStack Query はデフォルト装備ではなく、Server Component / Server Actions だけでは要件を満たせないときに追加する。

### データ取得

| ケース | 方法 |
|---|---|
| 通常の一覧・詳細表示 | Server Component で `fetch` し、必要なら Client Component に props で渡す |
| ポーリング (通知バッジ、オンライン状態など) | Server Component で初期取得 → `useQuery` に `initialData` として渡す |
| 無限スクロール | `useInfiniteQuery` (初期ページは Server Component で取得し `initialData` に渡す) |
| 検索オートコンプリート・絞り込みの即時反映 | `useQuery` (クライアント操作駆動の fetch) |
| 楽観的更新とセットの再取得 | `useQuery` + `useMutation` + `queryClient.invalidateQueries` |

**原則**:

- 初期表示に必要なデータは必ず Server Component で取得する。クライアントから `useQuery` だけで取得する構成は禁止 (ウォーターフォール・バンドル増・SEO 劣化のため)。
- `useQuery` を使う場合も、Server Component で取得した値を `initialData` に渡して初回レンダリングを即座に行う。
- URL に載せられるフィルタ・ソート・ページ番号は `searchParams` を使い、Server Component 側で再取得する。`useQuery` の引数に持たせない。

### データ更新

| ケース | 方法 |
|---|---|
| 通常のフォーム送信 (作成・編集・削除) | `useActionState` + Server Actions |
| 楽観的更新が必要な操作 (D&D・トグル・並び替え) | `useMutation` + `onMutate` による楽観的更新 |
| リアルタイム性が求められる操作 (チェックボックス即時反映など) | `useMutation` |

**原則**:

- サーバーへの単純な送信は Server Actions を使う。CSRF 保護・Progressive Enhancement・`revalidatePath` によるキャッシュ無効化を享受できるため。
- UX 上、サーバー応答を待てない操作のみ `useMutation` + 楽観的更新を使う。
- 両者を混在させる画面では、キャッシュ整合性のため **Server Actions 側は `revalidatePath` / `revalidateTag`**、**`useMutation` 側は `queryClient.invalidateQueries`** を必ず呼ぶ。

### 判断フローチャート

```
[データ取得]
ポーリング / 無限スクロール / 楽観的更新 / クライアント操作駆動の再取得 が必要?
├─ いいえ → Server Component で取得し Client に props で渡す
└─ はい   → Server Component で初期取得 → initialData として useQuery に渡す

[データ更新]
UX 上、サーバー応答を待たずに即座に UI 反映する必要がある?
├─ いいえ → useActionState + Server Actions
└─ はい   → useMutation + onMutate (楽観的更新)
```

### 実装例: Server で取得 → Client で表示

```tsx
// app/(authenticated)/tasks/page.tsx  (Server Component)
import { getTasks } from '@/features/task/api/get-tasks';
import { TaskBoard } from '@/features/task/components/task-board';

export default async function TasksPage() {
  const tasks = await getTasks();
  return <TaskBoard initialTasks={tasks} />;
}
```

```tsx
// features/task/components/task-board.tsx  (Client Component)
'use client';

import { useState } from 'react';
import type { Task } from '../schemas/task.schema';

type Props = { initialTasks: Task[] };

export function TaskBoard({ initialTasks }: Props) {
  const [tasks, setTasks] = useState(initialTasks);
  // D&D・フィルタなどのインタラクションのみ Client 側で処理
  return {/* ... */};
}
```

### 実装例: Server で取得 → useQuery でポーリング

```tsx
// features/notification/components/notification-badge.tsx
'use client';

import { useQuery } from '@tanstack/react-query';
import { fetchUnreadCount } from '../api/fetch-unread-count';

type Props = { initialCount: number };

export function NotificationBadge({ initialCount }: Props) {
  const { data } = useQuery({
    queryKey: ['notifications', 'unread-count'],
    queryFn: fetchUnreadCount,
    initialData: initialCount,
    staleTime: 30_000,
    refetchInterval: 30_000, // 30 秒ごとに再取得
  });

  return <span>{data}</span>;
}
```

## Server Actions 実装ルール

Server Actions の返り値を直接 UI に反映する。`useState` / `useEffect` / `useReducer` での管理は禁止。

| 処理 | 方針 |
|---|---|
| 状態管理 | Server Actions の返り値をそのまま使用 |
| ローディング | `useTransition` / `isPending` で管理 |
| エラーハンドリング | `try-catch` で実装。エラー時は空配列やフォールバック値を返し UI を保護 |
| ユーザーフィードバック | 成功・エラーメッセージを返り値から表示 |
| リダイレクト | `redirect()` を Server Actions 内で呼び出す |
| フォームリセット | 返り値を受けてクライアント側でリセット処理 |
| データ更新 / キャッシュ | `revalidatePath` / `revalidateTag` で再検証 |
| セキュリティ | Next.js 標準の CSRF 保護を享受 |
| パフォーマンス | Streaming / Suspense を活用してプリフェッチ・段階的表示を行う |

## タイムアウト

**Server Actions / SSR (`fetch` 直接使用)**

```ts
const controller = new AbortController();
const timeoutId = setTimeout(() => controller.abort(), 10_000);
const res = await fetch(url, { signal: controller.signal });
clearTimeout(timeoutId);
```

**CSR (TanStack Query)**

```ts
useQuery({
  queryFn: async () => {
    const res = await fetch(url, { signal: AbortSignal.timeout(10_000) });
    return res.json();
  },
});
```

## リトライ

**Server Actions / SSR**

- ネットワークエラー・5xx 系エラーは最大 3 回リトライする。
- リトライ間隔は指数バックオフ (1 秒 → 2 秒 → 4 秒)。
- 4xx 系エラーはリトライしない。

**CSR (TanStack Query)**

```ts
useQuery({
  queryFn: fetchData,
  retry: 3,
  retryDelay: (attemptIndex) => Math.min(1000 * 2 ** attemptIndex, 4000),
  retryOnMount: false,
});
```

## レスポンスの型チェック

API レスポンスは必ず zod でバリデーションし、型安全を保証する。

```ts
const schema = z.object({ /* ... */ });
const parsed = schema.safeParse(await res.json());
if (!parsed.success) {
  logger.error(parsed.error);
  return [];
}
```

## ヘッダーの設定

```ts
const headers: HeadersInit = {
  'Content-Type': 'application/json',
  ...(token && { Authorization: `Bearer ${token}` }),
};
```

## キャッシュ

- **SSR / ISR**: `fetch` に `{ next: { revalidate: 60 } }` を設定する。
- **CSR**: TanStack Query の `staleTime` を 60,000ms に設定する。Server Component で取得した値を `initialData` として渡す。
