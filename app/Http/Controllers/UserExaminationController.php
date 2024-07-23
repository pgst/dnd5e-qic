<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\UserExamination;
use Illuminate\Http\Request;

/**
 * ユーザー回答コントローラ
 */
class UserExaminationController extends Controller
{
    /**
     * 回答準備画面を表示
     */
    public function start()
    {
        $count = Examination::where('enabled', 1)->count();
        $itemsPerExam = env('ITEMS_PER_EXAM');
        $passingScore = (int)ceil($itemsPerExam * env('PASSING_RATE'));
        
        return view('user-examination.start', compact('count', 'itemsPerExam', 'passingScore'));
    }

    /**
     * 回答欄を用意する処理
     * 
     * @param Request $request
     */
    public function store(Request $request)
    {
        $itemsPerExam = $request->exam;   // 問題文数
        $passingScore = $request->score;  // 合格点数

        // ログインユーザーの未提出の回答欄があれば取得する
        $userExaminations = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->get();

        if ($userExaminations->count() > 0) {
            $message = '前回の試験が完了していないので再度表示します。';

            // 未提出だがすべて解答済みの場合は、提出確認画面に遷移
            if ($userExaminations->where('selected_answer', null)->count() == 0) {
                return redirect()->route('user-examination.confirm')
                    ->with('message',  $message);
            }
        }

        // 挑戦回数の最大値を取得して、今回の挑戦回数を設定
        $challenge_num = UserExamination::where('user_id', auth()->id())
            ->max('challenge_num') + 1;
        
        // 未提出がない場合は、問題文からランダムに取得する
        if ($userExaminations->count() == 0) {
            $examinations = Examination::where('enabled', 1)->inRandomOrder()->limit($itemsPerExam)->get();

            // 回答欄を準備
            $question_num = 1;  // 初期値を1に設定
            foreach ($examinations as $examination) {
                $userExamination = new UserExamination();             // 回答欄オブジェクトを生成
                $userExamination->user_id = auth()->id();             // ログインユーザーのIDを設定
                $userExamination->examination_id = $examination->id;  // 問題文のIDを設定
                $userExamination->enabled = 1;                        // 有効フラグを設定
                $userExamination->challenge_num = $challenge_num;     // 挑戦回数を設定
                $userExamination->question_num = $question_num;       // 問題番号を設定
                $userExamination->save();                             // 1レコード保存
                $question_num++;                                      // 問題番号をインクリメント
            }
        }

        $userExamFirst = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->where('question_num', 1)->first();

        if (empty($userExamFirst)) {
            return redirect()->route('user-examination.start')
                ->with('message', '問題文がありません。');
        } else {
            return redirect()->route('user-examination.select', ['user_examination' => $userExamFirst->id])
            ->with('message', $message ?? '');
        }
    }

    /**
     * 問題文と選択肢を表示
     * 
     * @param UserExamination $userExamination
     */
    public function select(UserExamination $userExamination)
    {
        $itemsPerExam = env('ITEMS_PER_EXAM');
        
        return view('user-examination.select', compact('userExamination', 'itemsPerExam'));
    }

    /**
     * 回答した選択肢を保存
     * 
     * @param Request $request
     * @param UserExamination $userExamination
     */
    public function update(Request $request, UserExamination $userExamination)
    {
        // 「はい」or「いいえ」の選択がない場合は、戻ってエラーメッセージを表示
        if ($request->selected_answer == null) {
            return back()->with('message', '「はい」か「いいえ」を選択してください。');
        }

        $validated = $request->validate([
            'selected_answer' => 'required',
        ]);

        $validated['cleared'] =
            ($validated['selected_answer'] == $userExamination->examination->correct_answer) ? 1 : 0;

        $validated['user_id'] = auth()->id();

        $userExamination->update($validated);

        // 最終問題の場合は、提出確認画面に遷移
        if ($userExamination->question_num == env('ITEMS_PER_EXAM')) {
            return redirect()->route('user-examination.confirm');
        }

        // 全ての問題が回答済みなら、提出確認画面に遷移
        $userExaminations = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->where('selected_answer', '!=', null)->get();
        if ($userExaminations->count() == env('ITEMS_PER_EXAM')) {
            return redirect()->route('user-examination.confirm');
        }

        // 次の問題に遷移
        $next = $userExamination->id + 1;
        return redirect()->route('user-examination.select', ['user_examination' => $next]);
    }

    /**
     * 提出確認画面を表示
     */
    public function confirm()
    {
        $userExams = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->get();
        
        if ($userExams->where('selected_answer', '==', null)) {
            return redirect()->route('user-examination.start');
        }
        
        $itemsPerExam = env('ITEMS_PER_EXAM');
        
        return view('user-examination.confirm', compact('userExams', 'itemsPerExam'));
    }

    /**
     * 結果画面を表示
     * 
     * @param Request $request
     */
    public function result(Request $request)
    {
        $itemsPerExam = env('ITEMS_PER_EXAM');
        $passingScore = (int)ceil($itemsPerExam * env('PASSING_RATE'));

        $userExaminations = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->get();
        $correctCount = $userExaminations->where('cleared', 1)->count();
        $score = $correctCount;

        $name = auth()->user()->name;
        $result = ($score >= $passingScore) ? '合格' : '不合格';

        // 回答欄の有効フラグを無効にする
        foreach ($userExaminations as $userExamination) {
            $userExamination->enabled = 0;
            $userExamination->save();
        }

        return view('user-examination.result', compact('name', 'score', 'result'));
    }
}
