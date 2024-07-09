<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\UserExamination;
use Illuminate\Http\Request;

class UserExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userExams = UserExamination::where('user_id', auth()->id())->paginate(1);
        
        // パラメーターのpageを取得
        $page = request('page');
        if (empty($page)) { $page = 1; }

        return view('user-examination.index', compact('userExams', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $count = Examination::where('enabled', 1)->count();
        $itemsPerExam = env('ITEMS_PER_EXAM');
        $passingScore = (int)ceil($itemsPerExam * env('PASSING_RATE'));
        
        return view('user-examination.create', compact('count', 'itemsPerExam', 'passingScore'));
    }

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
        if ($userExaminations->where('question_num', '>', 0)->count() > 0) {
            $message = '未提出の解答があるので再度表示します。';
        }

        // 挑戦回数の最大値を取得して、今回の挑戦回数を設定
        $challenge_num = $userExaminations->max('challenge_num') + 1;
        
        // 未提出がない場合は、問題文からランダムに取得する
        if ($userExaminations->where('question_num', '>', 0)->count() == 0) {
            $examinations = Examination::where('enabled', 1)->inRandomOrder()->limit($itemsPerExam)->get();

            // 回答欄を準備
            $question_num = 1;  // 初期値を1に設定
            foreach ($examinations as $examination) {
                $userExamination = new UserExamination();             // 回答欄オブジェクトを生成
                $userExamination->user_id = auth()->id();             // ログインユーザーのIDを設定
                $userExamination->examination_id = $examination->id;  // 問題文のIDを設定
                $userExamination->challenge_num = $challenge_num;     // 挑戦回数を設定
                $userExamination->question_num = $question_num;       // 問題番号を設定
                $userExamination->save();                             // 1レコード保存
                $question_num++;                                      // 問題番号をインクリメント
            }
        }
        
        return redirect()->route('user-examination.index')->with('message', $message ?? '');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserExamination $userExamination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserExamination $userExamination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserExamination $userExamination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserExamination $userExamination)
    {
        //
    }
}
