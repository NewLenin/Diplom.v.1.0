<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiaryController extends Controller
{
    public function Index(Request $r)
    {
        $today = Carbon::today('Asia/Yekaterinburg')->format('Y-m-d');
        $color = User::where('id', Auth::id())->first();
        $todayNote = Note::where('dating', $today)->where('id', Auth::id())->first();

        return view('diary', [
            // 'res' => $res,
            'today' => $today,
            'color' => $color,
            'todayNote' => $todayNote
        ]);
    }
    public function showNote(Request $r)
    {
        //список месяцев
        $_monthsList = array(
            "01" => "январь",
            "02" => "февраль",
            "03" => "март",
            "04" => "апрель",
            "05" => "май",
            "06" => "июнь",
            "07" => "июль",
            "08" => "август",
            "09" => "сентябрь",
            "10" => "октябрь",
            "11" => "ноябрь",
            "12" => "декабрь"
        );

        //дата для сравнения с бд
        $convert = strtotime($r->date);
        $date = date('Y-M', $convert);

        //возвращает количество дней в выбранном месяце
        $month = date('m', $convert);
        $year = date('Y', $convert);
        $countDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        //декоративнае название месяца
        $monthValue = $_monthsList[date($month)];

        //нулёвки
        $goodDays = 0;
        $normalDays = 0;
        $badDays = 0;
        $warning = array();

        //все записки пользователя
        $result = Note::where('user_id', Auth::id())->get();

        //пергонка всех записей для статистики
        foreach ($result as $res) {
            $conv = strtotime($res->dating);
            $i = date('Y-M', $conv);
            if ($i == $date) {

                switch ($res->status) {
                    case 'Отличный':
                        $goodDays = $goodDays + 1;
                        break;
                    case 'Нормальный':
                        $normalDays = $normalDays + 1;
                        break;
                    case 'Плохой':
                        $badDays = $badDays + 1;
                        break;
                }

                if ($res->warning != null) {
                    array_push($warning, $res->warning);
                }
            }

        }
        $vals = array_count_values($warning);
        $nonDays = $countDays - ($goodDays + $normalDays + $badDays);

        //отображение записи по дате
        $note = Note::where('dating', $r->date)->where('user_id', Auth::id())->first();
        if ($note && isset($note)) {
            return response()->json([
                'monthValue' => $monthValue,
                'goodDays' => $goodDays,
                'normalDays' => $normalDays,
                'badDays' => $badDays,
                'nonDays' => $nonDays,
                'warningValue' => $vals,
                'message' => $note->message,
                'dating' => $note->dating,
                'status' => $note->status,
                'warning' => $note->warning,
            ], 200);
        }

        //пустая дата
        return response()->json([
            'monthValue' => $monthValue,
            'goodDays' => $goodDays,
            'normalDays' => $normalDays,
            'badDays' => $badDays,
            'nonDays' => $nonDays,
            'warningValue' => $vals,
            'errors' => 'Error'
        ], 400);

    }
    public function addNote(Request $r)
    {

        $validator = Validator::make($r->all(), [
            'message' => 'string|required',
            'date' => 'date|required',
            'status' => 'nullable|string',
            'warning' => 'nullable|string'
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            return response()->json($validator->errors(), 400);
        }
        $check = Note::where('dating', $r->date)->where('user_id', Auth::id())->first();
        if ($check) {
            Note::where('dating', $r->date)->where('user_id', Auth::id())->update([
                'warning' => $r->warning,
                'status' => $r->status,
                'message' => $r->message
            ]);
        } else {
            Note::create([
                'user_id' => Auth::id(),
                'dating' => $r->date,
                'warning' => $r->warning,
                'status' => $r->status,
                'message' => $r->message
            ]);
        }

        return response()->json(['diary' => 'success'], 200);
    }
}
