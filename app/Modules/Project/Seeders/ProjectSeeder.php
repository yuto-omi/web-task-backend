<?php

namespace App\Modules\Project\Seeders;

use App\Modules\Project\Models\Project;
use App\Modules\Project\Models\ProjectPhase;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $admin = $users->first();

        // ----------------
        // プロジェクト1: 進行中
        // ----------------
        $project1 = Project::create([
            'created_by' => $admin->id,
            'name' => '株式会社サンプル コーポレートサイトリニューアル',
            'client_name' => '株式会社サンプル',
            'status' => 'in_progress',
            'start_date' => '2026-03-01',
            'deadline' => '2026-05-31',
            'estimated_hours' => 120.0,
        ]);

        $project1->members()->attach($users->take(3)->pluck('id'));

        $phases1 = [
            ['name' => '要件定義', 'status' => 'completed', 'start_date' => '2026-03-01', 'end_date' => '2026-03-07', 'sort_order' => 1],
            ['name' => 'ワイヤーフレーム', 'status' => 'completed', 'start_date' => '2026-03-08', 'end_date' => '2026-03-21', 'sort_order' => 2],
            ['name' => 'デザイン', 'status' => 'in_progress', 'start_date' => '2026-03-22', 'end_date' => '2026-04-11', 'sort_order' => 3],
            ['name' => 'コーディング', 'status' => 'not_started', 'start_date' => '2026-04-12', 'end_date' => '2026-05-10', 'sort_order' => 4],
            ['name' => 'テスト', 'status' => 'not_started', 'start_date' => '2026-05-11', 'end_date' => '2026-05-24', 'sort_order' => 5],
            ['name' => '納品', 'status' => 'not_started', 'start_date' => '2026-05-31', 'end_date' => '2026-05-31', 'sort_order' => 6],
        ];

        foreach ($phases1 as $phaseData) {
            $phase = ProjectPhase::create([
                'project_id' => $project1->id,
                'assignee_id' => $users->random()->id,
                ...$phaseData,
            ]);

            // 各フェーズにタスクを作成
            $this->createPhaseTasks($project1, $phase, $users);
        }

        // ----------------
        // プロジェクト2: 未着手
        // ----------------
        $project2 = Project::create([
            'created_by' => $admin->id,
            'name' => '株式会社テック ECサイト構築',
            'client_name' => '株式会社テック',
            'status' => 'not_started',
            'start_date' => '2026-05-01',
            'deadline' => '2026-08-31',
            'estimated_hours' => 200.0,
        ]);

        $project2->members()->attach($users->take(4)->pluck('id'));

        $phases2 = [
            ['name' => '要件定義', 'status' => 'not_started', 'start_date' => '2026-05-01', 'end_date' => '2026-05-14', 'sort_order' => 1],
            ['name' => 'デザイン', 'status' => 'not_started', 'start_date' => '2026-05-15', 'end_date' => '2026-06-14', 'sort_order' => 2],
            ['name' => 'コーディング', 'status' => 'not_started', 'start_date' => '2026-06-15', 'end_date' => '2026-08-15', 'sort_order' => 3],
            ['name' => 'テスト・納品', 'status' => 'not_started', 'start_date' => '2026-08-16', 'end_date' => '2026-08-31', 'sort_order' => 4],
        ];

        foreach ($phases2 as $phaseData) {
            $phase = ProjectPhase::create([
                'project_id' => $project2->id,
                'assignee_id' => $users->random()->id,
                ...$phaseData,
            ]);

            $this->createPhaseTasks($project2, $phase, $users);
        }

        // ----------------
        // プロジェクト3: 完了（アーカイブ）
        // ----------------
        $project3 = Project::create([
            'created_by' => $admin->id,
            'name' => '合同会社デザイン ランディングページ制作',
            'client_name' => '合同会社デザイン',
            'status' => 'completed',
            'start_date' => '2026-01-10',
            'deadline' => '2026-02-28',
            'estimated_hours' => 40.0,
        ]);

        $project3->members()->attach($users->take(2)->pluck('id'));

        // ----------------
        // 個人タスク（project_id = NULL）
        // ----------------
        $personalTasks = [
            ['title' => 'Figmaのデザインシステム整理', 'priority' => 'high', 'due_date' => '2026-04-15', 'status' => 'in_progress'],
            ['title' => 'コードレビューの対応', 'priority' => 'medium', 'due_date' => '2026-04-12', 'status' => 'pending'],
            ['title' => '来週のMTG資料作成', 'priority' => 'medium', 'due_date' => '2026-04-18', 'status' => 'pending'],
            ['title' => 'Tailwind CSS v4 の調査', 'priority' => 'low', 'due_date' => null, 'status' => 'pending'],
            ['title' => 'ポートフォリオ更新', 'priority' => 'low', 'due_date' => null, 'status' => 'pending'],
        ];

        foreach ($personalTasks as $taskData) {
            Task::create([
                'project_id' => null,
                'assignee_id' => $admin->id,
                'created_by' => $admin->id,
                ...$taskData,
            ]);
        }
    }

    /**
     * フェーズに紐づくタスクを作成する
     *
     * @param  Collection<int, User>  $users
     */
    private function createPhaseTasks(Project $project, ProjectPhase $phase, $users): void
    {
        $tasksByPhase = [
            '要件定義' => ['クライアントヒアリング', '競合サイト調査', '要件定義書作成', 'スケジュール確定'],
            'ワイヤーフレーム' => ['サイトマップ作成', 'TOPページWF作成', '下層ページWF作成', 'WFフィードバック対応'],
            'デザイン' => ['デザインカンプ作成（TOP）', 'デザインカンプ作成（下層）', 'デザインレビュー対応', 'SP版デザイン作成'],
            'コーディング' => ['HTML/CSS コーディング', 'JavaScriptの実装', 'CMS組み込み', 'レスポンシブ対応'],
            'テスト' => ['ブラウザ動作確認', 'リンクチェック', '表示崩れ修正', 'クライアント確認'],
            '納品' => ['本番環境へのアップロード', '納品物一式送付'],
            'テスト・納品' => ['ブラウザ動作確認', 'クライアント最終確認', '本番公開'],
        ];

        $titles = $tasksByPhase[$phase->name] ?? ['タスク1', 'タスク2', 'タスク3'];

        foreach ($titles as $i => $title) {
            Task::create([
                'project_id' => $project->id,
                'phase_id' => $phase->id,
                'assignee_id' => $users->random()->id,
                'created_by' => $users->first()->id,
                'title' => $title,
                'status' => $phase->status === 'completed' ? 'done' : 'pending',
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                'estimated_hours' => [1.0, 2.0, 3.0, 4.0][array_rand([1.0, 2.0, 3.0, 4.0])],
                'sort_order' => $i + 1,
            ]);
        }
    }
}
