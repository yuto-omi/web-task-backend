# フォーム実装

フォームは **react-hook-form + zod + Server Actions** の組み合わせを標準とする。zod schema は 1 つだけ定義し、**クライアントバリデーションと Server Actions のバリデーションで共通利用する**。

## 実装フロー

1. **zod schema を定義** — `features/{domain}/schemas/` に配置。エラーメッセージは日本語で記述する。
2. **型を導出** — `type TaskFormInput = z.infer<typeof taskFormSchema>`
3. **Server Actions を実装** — schema で `safeParse` してから Service 層を呼ぶ。
4. **Client Component でフォームを実装** — `useForm` + `zodResolver` + `useActionState` (React 19) を組み合わせる。
5. **送信中の UI 制御** — `useActionState` の `isPending` でボタンを disable する。
6. **成功・失敗フィードバック** — `react-hot-toast` でユーザーに通知する。

## zod schema の例

```ts
// features/task/schemas/task.schema.ts
import { z } from 'zod';

export const taskFormSchema = z.object({
  title: z.string().min(1, 'タイトルは必須です').max(100, '100 文字以内で入力してください'),
  estimatedHours: z
    .number()
    .min(0, '0 以上で入力してください')
    .multipleOf(0.5, '0.5 時間単位で入力してください'),
  assigneeId: z.string().uuid().nullable(),
  dueDate: z.string().datetime().nullable(),
});

export type TaskFormInput = z.infer<typeof taskFormSchema>;
```

## Server Actions の例

```ts
// features/task/api/create-task.ts
'use server';

import { revalidatePath } from 'next/cache';
import { cookies } from 'next/headers';
import { apiFetch } from '@/lib/api/api-fetch';
import { taskFormSchema, type TaskFormInput } from '../schemas/task.schema';

type ActionResult =
  | { success: true; message: string }
  | { success: false; errors: Partial<Record<keyof TaskFormInput, string[]>> };

export async function createTaskAction(
  _prevState: ActionResult | null,
  formData: FormData,
): Promise<ActionResult> {
  const cookieStore = await cookies();
  if (!cookieStore.get('auth_token')) {
    return { success: false, errors: { title: ['認証が必要です'] } };
  }

  const parsed = taskFormSchema.safeParse({
    title: formData.get('title'),
    estimatedHours: Number(formData.get('estimatedHours')),
    assigneeId: formData.get('assigneeId') || null,
    dueDate: formData.get('dueDate') || null,
  });

  if (!parsed.success) {
    return { success: false, errors: parsed.error.flatten().fieldErrors };
  }

  try {
    await apiFetch('/api/tasks', {
      method: 'POST',
      body: JSON.stringify(parsed.data),
    });
    revalidatePath('/tasks');
    return { success: true, message: 'タスクを作成しました' };
  } catch {
    return { success: false, errors: { title: ['作成に失敗しました'] } };
  }
}
```

## Client Component の例

```tsx
// features/task/components/task-form.tsx
'use client';

import { useActionState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import toast from 'react-hot-toast';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { taskFormSchema, type TaskFormInput } from '../schemas/task.schema';
import { createTaskAction } from '../api/create-task';

export function TaskForm() {
  const [state, formAction, isPending] = useActionState(createTaskAction, null);

  const {
    register,
    formState: { errors },
  } = useForm<TaskFormInput>({
    resolver: zodResolver(taskFormSchema),
    mode: 'onBlur',
  });

  useEffect(() => {
    if (state?.success) toast.success(state.message);
  }, [state]);

  return (
    <form action={formAction}>
      <Input {...register('title')} />
      {errors.title && <p>{errors.title.message}</p>}
      {state && !state.success && state.errors.title && (
        <p>{state.errors.title[0]}</p>
      )}
      <Button type="submit" disabled={isPending}>
        {isPending ? '作成中...' : '作成'}
      </Button>
    </form>
  );
}
```

## フォーム実装のルール

- **zod schema は単一ソース**。フロントエンドとサーバーで同じ schema を import する。重複定義は禁止。
- **エラー表示は 2 系統**: クライアントバリデーション (`errors.xxx.message`) と Server Actions の返り値 (`state.errors.xxx`) の両方を表示する。
- **送信中は必ず disabled**。二重送信を防ぐため `isPending` でボタンを無効化する。
- **`useState` でフォーム値を管理しない**。react-hook-form または `FormData` に一元化する。
- **成功時のリダイレクト**は Server Actions 内で `redirect()` を呼ぶ。クライアント側の `useEffect` で遷移させない。
