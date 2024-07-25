<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExamination extends Model
{
    use HasFactory;

    public function examination()
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }

    protected $fillable = [
        'selected_answer',  // 選択した答え
        'enabled',          // 有効かどうか
        'cleared',          // 正解したかどうか
        'challenge_num',    // 挑戦回数
        'question_num',     // 問題番号
    ];
}
