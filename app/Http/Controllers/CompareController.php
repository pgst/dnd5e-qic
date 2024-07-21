<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserExamination;

/**
 * 受験結果参照コントローラ
 */
class CompareController extends Controller
{
    /**
     * 受験結果を表示
     */
    public function index()
    {
        $userExams = UserExamination::with('examination')
            ->where('user_id', auth()->id())
            ->where('enabled', 0)
            ->orderBy('id', 'ASC')
            ->paginate(env('ITEMS_PER_EXAM'));
        
        if ($userExams->isEmpty()) {
            return redirect()->route('user-examination.start')
                ->with('message', '先に受験してください。');
        }

        return view('compare.index', compact('userExams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
