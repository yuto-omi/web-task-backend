# セキュリティ

## XSS 対策

- `dangerouslySetInnerHTML` は原則禁止。使用する場合は `dompurify` でサニタイズ必須。
- `<a>` / `<iframe>` の `src` / `href` に動的値を埋め込む際は `javascript:` プロトコルを検証する。

## 環境変数

- `NEXT_PUBLIC_` はブラウザ露出が許容される非機密情報のみに限定する。
- API キー・DB 接続情報・認証シークレットは Server Components / Server Actions 内でのみ参照する。
- サーバー専用ロジックを含むファイルには `import 'server-only'` を記述する。

## 認証・認可

- クライアント側の「ボタン非表示」は UX のためであり、セキュリティではない。
- Server Actions / API Routes の冒頭で必ず `cookies()` から `auth_token` を取得し認可を再実行する。
- 認証が必要なルートは `middleware.ts` で一括管理する。

## CSRF 対策

- **Server Actions を中継として使用**することで Next.js 標準の CSRF 保護を享受する (推奨)。
- API Routes を直接呼ぶ場合は `getCsrfToken()` で `X-CSRF-Token` ヘッダーを付与する。
- Cookie は `SameSite=Lax` を徹底し、CORS は許可 Origin のみを指定する。

## エラーハンドリング

- ユーザー向けメッセージ (toast 等) にはスタックトレース・内部構造を含めない。
- 詳細なエラーはサーバーログのみに記録する。

## 依存関係

- `npm audit` を定期実行して脆弱性を把握する。
- 不要なライブラリは追加しない。セキュリティ関連ライブラリは特に慎重に選定する。
