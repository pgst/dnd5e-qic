<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Examination;

class CsvImportController extends Controller
{
    public function show()
    {
        return view('import-csv');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $header = array_shift($sheetData); // ヘッダー行を削除
        echo implode(',', $header) . '<br>';

        // 各行保存処理
        foreach ($sheetData as $row) {
            $examination = new Examination();
            $examination->id             = $row[0];  // ID
            $examination->question_txt   = $row[1];  // 問題文
            $examination->correct_answer = $row[2];  // 正解
            $examination->enabled = $row[3] ? 1 : 0; // 有効フラグ
            $examination->save();

            // 行を画面出力
            echo implode(',', $row) . '<br>';
        }
    }
}
