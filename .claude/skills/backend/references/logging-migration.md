# ロギング / マイグレーション

## ロギング

- **チャンネル**: `daily` ドライバーを使用し、日付ごとにファイルを分割する。
- **レベル**: 本番環境は `warning` 以上のみ記録する。
- **機密情報禁止**: ログにパスワード・トークン・個人情報を含めない。`$request->except('password')` を使用する。
- **フォーマット**: `日時 | レベル | メッセージ | コンテキスト`

```php
Log::warning('外部 API 呼び出し失敗', [
    'url'    => $url,
    'status' => $response->status(),
    // password・token 等は含めない
]);
```

---

## マイグレーション

- マイグレーションは **べき等** に書く（`dropIfExists`、`down()` を省略しない）。
- カラムには必ずコメントを付与する（`->comment('説明')`）。
- インデックスは外部キー・検索条件に使うカラムに必ず付与する。
- 本番データがあるテーブルへの破壊的変更（カラム削除・型変更）は段階的にマイグレーションを分割する。

```php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique()->comment('メールアドレス');
        $table->string('password')->comment('ハッシュ化済みパスワード');
        $table->timestamps();
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::dropIfExists('users');
}
```
