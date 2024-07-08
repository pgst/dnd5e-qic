<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    public function userExaminations()
    {
        return $this->hasMany(UserExamination::class);
    }

    protected $fillable = [
        'question_txt',    // 問題文
        'correct_answer',  // 正解
        'enabled',         // 有効フラグ
    ];
}
