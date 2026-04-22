# 実装方針・コーディング規約

## 設計

- **単一責任**: コンポーネントの責任範囲を明確にする。1 ファイル = 1 主要コンポーネント。
- **Feature-Sliced Design**: `features/` 配下はドメイン単位で分割する。
- **型設計**: Props・内部状態のインターフェースを厳密に定義する。
- **依存関係の方向**: `features/{domain}/` から `components/shared/` `components/ui/` への import は OK。逆方向、および `features` 同士の相互 import は禁止。

---

## Rendering Strategy

- **Server First**: 可能な限り Server Components (RSC) で実装し、クライアントへ送る JS 量を削減する。
- **Client Component の局所化**: Hooks / ブラウザ API が必要な箇所のみ `"use client"` を付与し、最小単位で切り出す。
- **データ取得は Server Component で完結**: 初期表示に必要なデータはサーバー側で取得し、Client Component には props で渡す。詳細は「データ取得・更新の使い分け」を参照。

---

## TypeScript

- **strict mode 必須**: `tsconfig.json` で `strict: true` を有効化する。`noUncheckedIndexedAccess` も推奨。
- **`any` 禁止**: 型が分からない場合は `unknown` を使い、型ガードで絞り込む。
- **`as` によるキャスト原則禁止**: `as unknown as T` を含めて型アサーションは原則禁止。zod の `safeParse` で型を保証する。例外的に DOM 操作で必要な場合のみ許容する。
- **non-null assertion (`!`) 禁止**: `value!` の代わりに `if (!value) throw new Error(...)` で明示的にチェックする。
- **`type` を優先**: `interface` ではなく `type` を使う (union 型・交差型との一貫性のため)。React コンポーネントの Props も `type Props = { ... }` で統一。
- **`enum` 禁止**: TypeScript の `enum` ではなく `as const` オブジェクトまたは union 型を使う (バンドルサイズ・tree-shaking のため)。

```ts
// ❌ NG
enum TaskStatus {
  Todo = 'todo',
  Done = 'done',
}

// ✅ OK
const TASK_STATUS = {
  TODO: 'todo',
  DONE: 'done',
} as const;
type TaskStatus = (typeof TASK_STATUS)[keyof typeof TASK_STATUS];
```

---

## 命名規則

| 対象                    | 規則                                | 例                                     |
| ----------------------- | ----------------------------------- | -------------------------------------- |
| コンポーネント (関数)   | `PascalCase`                        | `TaskCard`, `UserAvatar`               |
| コンポーネントファイル  | `kebab-case.tsx`                    | `task-card.tsx`, `user-avatar.tsx`     |
| カスタムフック          | `use` + `camelCase`                 | `useTaskFilter`, `useDebounce`         |
| Server Actions          | 動詞 + 名詞 + `Action`              | `createTaskAction`, `updateUserAction` |
| 型・interface           | `PascalCase`                        | `Task`, `TaskFormInput`                |
| 定数                    | `UPPER_SNAKE_CASE`                  | `MAX_RETRY_COUNT`, `API_BASE_URL`      |
| 変数・関数              | `camelCase`                         | `taskList`, `fetchTasks`               |
| boolean 変数            | `is` / `has` / `can` プレフィックス | `isLoading`, `hasError`, `canEdit`     |
| イベントハンドラ (内部) | `handle` プレフィックス             | `handleClick`, `handleSubmit`          |
| イベントハンドラ Props  | `on` プレフィックス                 | `onClick`, `onSubmit`                  |

- Magic string / number は禁止。すべて定数として定義する。

---

## パフォーマンス

- `useCallback`: 子コンポーネントへ渡す関数・依存配列に含まれる関数は必ずラップする。
- `memo` / `useMemo`: リストアイテム等で再レンダリングが問題になる箇所に適用する。
- `useEffect` の乱用を避け、イベントハンドラ・`useMemo`・Server Actions で代替できないか検討する。
- **Core Web Vitals**: LCP < 2.5s, INP < 200ms, CLS < 0.1 を目標とする。
- **Lighthouse**: 主要ページは Performance スコア 90 以上を目標とする。
- **bundle analyzer**: `@next/bundle-analyzer` で定期的にバンドルサイズを確認する。
- **動的 import**: 重いライブラリ (チャート・エディタ・ガントチャート) は `dynamic(() => import(...), { ssr: false })` で遅延読み込みする。
- **画像**: `next/image` を必須とし、`priority` / `sizes` / `alt` を適切に設定する。

---

## エラーハンドリング

- **Server Actions**: 必ず `try-catch` で囲み、エラー時は `{ success: false, errors: ... }` 形式で返す。例外を throw しない。
- **Server Component**: データ取得失敗時は `error.tsx` でキャッチさせる。空配列フォールバックは UI を壊さない場合のみ使う。
- **Client Component**: ユーザー操作起因のエラーは `react-hot-toast` で通知する。`alert()` は禁止。
- **エラーメッセージ**: ユーザー向けは日本語で簡潔に。技術的な詳細 (スタックトレース等) は含めない。
- **エラー境界**: ルートセグメント単位で `error.tsx` を配置し、想定外のエラーをキャッチする。

---

## アクセシビリティ

- **セマンティック HTML**: `<div>` で済ませず、適切な要素 (`<button>`, `<nav>`, `<main>`, `<article>`) を使う。
- **`alt` 属性必須**: `<img>` および `next/image` には必ず `alt` を付与する。装飾画像は `alt=""`。
- **キーボード操作**: クリック可能な要素は Tab で到達でき、Enter / Space で操作できる。`<div onClick>` ではなく `<button>` を使う。
- **フォーカス管理**: モーダル開閉時のフォーカス制御は shadcn/ui (Radix) に任せる。自作する場合は `focus-visible` を必ず実装する。
- **ラベル必須**: フォーム入力には `<label>` または `aria-label` を付与する。
- **コントラスト比**: テキストと背景のコントラスト比は WCAG AA (4.5:1) 以上を確保する。

---

## 日付・時刻

- **ライブラリ**: `date-fns` を使用する。`moment.js` は禁止 (バンドルサイズ・メンテナンス停止のため)。
- **タイムゾーン**: 内部的には UTC で扱い、表示時のみ `Asia/Tokyo` に変換する。
- **シリアライズ**: サーバー ↔ クライアント間は ISO 8601 文字列 (`toISOString()`) で受け渡しする。`Date` オブジェクトを直接渡さない (シリアライズで型情報が失われるため)。
- **表示フォーマット**: 日本語ロケール (`format(date, 'yyyy年M月d日(E)', { locale: ja })`) を使う。
- **工数**: 0.5 時間単位で扱い、`number` 型で保持する (`1.5` = 1 時間 30 分)。zod schema で `multipleOf(0.5)` を必ず指定する。

---

## 環境変数

- **prefix 統一**: クライアント露出は `NEXT_PUBLIC_`、サーバー専用は prefix なし。
- **命名**: `UPPER_SNAKE_CASE`。用途別に prefix を付ける (`NEXT_PUBLIC_API_BASE_URL`, `DATABASE_URL`, `JWT_SECRET`)。
- **`.env.example` 必須**: リポジトリには `.env.example` を含め、必要な環境変数を全て列挙する (値は空またはダミー)。
- **読み込み**: `process.env.XXX` を直接使わず、`lib/env.ts` で zod スキーマを通して型安全に読み込む。

```ts
// lib/env.ts
import { z } from 'zod';

const envSchema = z.object({
  NEXT_PUBLIC_API_BASE_URL: z.string().url(),
  DATABASE_URL: z.string().url(),
  JWT_SECRET: z.string().min(32),
});

export const env = envSchema.parse(process.env);
```

---

## インポート順序

1. React 関連
2. 外部ライブラリ (`zod`, `lucide-react` など)
3. UI コンポーネント (`@/components/ui/...`)
4. 共通コンポーネント (`@/components/shared/...`)
5. Hooks / Utils / API (`@/lib/...`, `@/hooks/...`)
6. Features (`@/features/{domain}/...`)
7. 型 (`type` import)

- **`@` エイリアス必須**: `../` や `./` の相対パスは使わず、すべて `@/` から始まるパスで統一する。相対パスはリファクタリング時にパスが壊れやすく、ファイルの場所が一目でわからないため。

---

## コメント

- **Why を書く、What を書かない**: コードを読めば分かる「何をしているか」ではなく、「なぜそうしているか」を書く。
- **JSDoc**: 公開関数・カスタムフック・複雑なユーティリティには JSDoc コメントを付ける。
- **TODO / FIXME**: 一時的な対応には `// TODO(yyyy-mm-dd): 内容` の形で日付と内容を残す。放置禁止。
- **コメントアウトしたコードは削除**: Git で履歴が残るので、不要なコードはコメントアウトせず削除する。
- **shadcn/ui 優先**: UI 実装は shadcn/ui を優先する。同等の機能を自作しない。

---

## テスト

| レイヤー        | ツール                       | 対象                                               |
| --------------- | ---------------------------- | -------------------------------------------------- |
| ユニット / 統合 | Jest + React Testing Library | レンダリング、ユーザーインタラクション、API モック |
| E2E             | Cypress                      | 実際のユーザー操作フロー                           |

- **コロケーション**: テストファイル (`.test.tsx`) は対象コンポーネントと同じディレクトリに配置する。E2E テストのみ `tests/e2e/` に独立させる。
- **Storybook**: コンポーネント実装後は必ず Storybook (`.stories.tsx`) を同じディレクトリに追加する。Props の主要パターン (正常・ローディング・空・エラー) を網羅する。
- **ユーザー視点のテスト**: `getByRole` / `getByLabelText` を優先し、`getByTestId` は最終手段。
- **ユーザーインタラクション**: `@testing-library/user-event` を使用する。`fireEvent` は使わない。

---

## ロギング (サーバーサイド)

Server Actions・API Routes のサーバーサイド処理に適用する。

- **出力先**: ブラウザの `console.log` には機密情報を流さない。サーバーログはファイルに出力する。
- **フォーマット**: `日時 | レベル | メッセージ`
- **ファイル管理**: 日付ごとにファイルを分割し、保存期間は必要最低限とする。
- **機密情報**: ログに DB 接続情報・個人情報・スタックトレースを含めない。
- **開発環境**: `npm run dev` では `console.log` の使用は許容する。

---

## パッケージ管理

- **パッケージマネージャー**: `npm` を使用する。`yarn` / `pnpm` 混在禁止。
- **lockfile**: `package-lock.json` を必ずコミットする。
- **依存追加の判断基準**: 新規依存追加時は以下を確認する。
  - メンテナンスされているか (最終更新が 1 年以内)
  - バンドルサイズ ([bundlephobia](https://bundlephobia.com/) で確認)
  - 同等の機能が標準 API で実現できないか
  - 既存の依存ライブラリで代替できないか
- **`npm audit` の定期実行**: 月 1 回は脆弱性チェックする。
- **不要なライブラリは追加しない**。セキュリティ関連ライブラリは特に慎重に選定する。

---

## Lint / Format

- ESLint 9 の Flat Config を使用する。
- `eslint-config-next/core-web-vitals` + `eslint-config-next/typescript` を基本とする。
- `eslint-plugin-import-x` で import 順序を自動整形する。
- `eslint-config-prettier` を最後に重ねて Prettier との競合を回避する。

### Prettier

- フォーマット (タブ・クォート・セミコロン・改行) は Prettier に一任する。手動整形しない。
- 設定は `.prettierrc.json` で管理し、以下を基本とする:
  - `semi: true` (セミコロンあり)
  - `singleQuote: true` (シングルクォート)
  - `jsxSingleQuote: false` (JSX 内はダブルクォート)
  - `tabWidth: 2`
  - `trailingComma: "all"`
  - `printWidth: 100`
  - `endOfLine: "lf"`

### 自動化

- **VS Code**: 保存時に Prettier で自動整形 + ESLint で自動修正されるよう `.vscode/settings.json` を設定する。
- **pre-commit**: `lint-staged` + `husky` でコミット前に `prettier --write` と `eslint --fix` を自動実行する。
- **CI**: GitHub Actions で `npm run format:check` `npm run lint` `npm run typecheck` `npm run test` を実行する。

---

## Git コミット

- **Conventional Commits**: コミットメッセージは `type(scope): subject` 形式で書く。
  - `feat(task): タスク作成 API を追加`
  - `fix(form): バリデーションエラーが表示されない問題を修正`
  - `refactor(api): fetch ラッパーを統一`
- **type の種類**: `feat`, `fix`, `refactor`, `docs`, `style`, `test`, `chore`, `perf`
- **言語**: 日本語で書く。
- **粒度**: 1 コミット = 1 論理的変更。複数の変更を 1 コミットに混ぜない。
