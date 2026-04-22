# 状態管理

状態は以下の 4 種類に分類し、それぞれ適切な方法で管理する。**zustand の乱用を避ける**ことが最優先。

| 状態の種類 | 例 | 管理方法 |
|---|---|---|
| **サーバー状態** | タスク一覧、ユーザー情報、プロジェクト詳細 | Server Components + Server Actions + `revalidatePath` |
| **URL 状態** | フィルタ条件、ソート順、ページ番号、モーダル開閉 | `searchParams` / `useSearchParams` |
| **ローカル UI 状態** | フォーム入力値、トグル状態、アコーディオン開閉 | `useState` / react-hook-form |
| **グローバル UI 状態** | サイドバー開閉、テーマ、通知キュー、現在のチーム選択 | zustand |

## zustand を使うべきでないケース

- ❌ API から取得したデータ (→ Server Components で取得する)
- ❌ フォームの入力値 (→ react-hook-form で管理する)
- ❌ 1 コンポーネント内で完結する状態 (→ `useState` で十分)
- ❌ ページ間で共有したいフィルタ条件 (→ URL の `searchParams` を使う。リロード・共有・戻る操作に強いため)

## zustand を使うべきケース

- ✅ 複数の離れたコンポーネントで共有する UI 状態 (サイドバー開閉など)
- ✅ リアルタイム性が高く URL に載せたくない状態 (通知キュー・一時的なトースト)
- ✅ ユーザーのセッション単位で保持したい設定 (現在選択中のチーム ID など)

## store の配置と命名

- **配置**: `features/{domain}/stores/` または横断的なものは `stores/` 直下
- **命名**: `use{Purpose}Store` (例: `useSidebarStore`, `useCurrentTeamStore`)
- **1 store = 1 責務**。巨大な単一 store は禁止。ドメインごと・関心ごとに分割する。
- **selector で購読範囲を絞る**。コンポーネントは必要な state のみ購読し、不要な再レンダリングを防ぐ。

```ts
// stores/use-sidebar-store.ts
import { create } from 'zustand';

type SidebarStore = {
  isOpen: boolean;
  toggle: () => void;
  close: () => void;
};

export const useSidebarStore = create<SidebarStore>((set) => ({
  isOpen: true,
  toggle: () => set((s) => ({ isOpen: !s.isOpen })),
  close: () => set({ isOpen: false }),
}));

// 使用側: selector で必要な部分のみ購読
const isOpen = useSidebarStore((s) => s.isOpen);
```

## persist ミドルウェアの使用基準

- `localStorage` に保存するのは「ユーザー設定」レベルの情報のみ (サイドバー開閉状態、テーマなど)。
- 認証トークン・個人情報・機密情報は `persist` で保存しない。
- SSR との hydration mismatch を避けるため、persist された store を使うコンポーネントは `"use client"` 配下に限定する。
