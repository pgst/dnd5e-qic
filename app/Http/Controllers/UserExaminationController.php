<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\UserExamination;
use Illuminate\Http\Request;

class UserExaminationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $itemsPerExam = $request->exam;   // 問題文数
        $passingScore = $request->score;  // 合格点数

        // ログインユーザーの回答欄があれば取得する
        $userExaminations = UserExamination::where('user_id', auth()->id())->get();

        // 未提出がある場合は、メッセージを設定
        if ($userExaminations->where('enabled', 1)->count() > 0) {
            $message = '前回の試験が完了していないので再度表示します。';
        }

        // 挑戦回数の最大値を取得して、今回の挑戦回数を設定
        $challenge_num = $userExaminations->max('challenge_num') + 1;
        
        // 未提出がない場合は、問題文からランダムに取得する
        if ($userExaminations->where('enabled', 1)->count() == 0) {
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
            return redirect()->route('user-examination.start')->with('message', '問題文がありません。');
        } else {
            return redirect()->route('user-examination.select', ['user_examination' => $userExamFirst->id])
                ->with('message', $message ?? '');
        }
    }

    /**
     * Update the specified resource in storage.
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
     * 
     */
    public function start()
    {
        $count = Examination::where('enabled', 1)->count();
        $itemsPerExam = env('ITEMS_PER_EXAM');
        $passingScore = (int)ceil($itemsPerExam * env('PASSING_RATE'));
        
        return view('user-examination.start', compact('count', 'itemsPerExam', 'passingScore'));
    }

    /**
     * 
     */
    public function select(UserExamination $userExamination)
    {
        $itemsPerExam = env('ITEMS_PER_EXAM');
        
        return view('user-examination.select', compact('userExamination', 'itemsPerExam'));
    }

    /**
     * 
     */
    public function confirm()
    {
        $itemsPerExam = env('ITEMS_PER_EXAM');
        $userExams = UserExamination::where('user_id', auth()->id())
            ->where('enabled', 1)->get();
        
        return view('user-examination.confirm', compact('userExams', 'itemsPerExam'));
    }

    /**
     * 
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
