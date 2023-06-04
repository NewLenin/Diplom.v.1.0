<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Note;
use App\Models\Transaction;
use App\Models\Purpose;
use App\Models\Finance;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    public function Index()
    {
        //работа с датами
        $today = Carbon::today('Asia/Yekaterinburg')->format('Y-m-d');
        // $yesterday = Carbon::yesterday('Asia/Yekaterinburg');
        $treeDaysAgo = Carbon::today('Asia/Yekaterinburg')->subDays(3);
        //блок с задачами
        $purposeActive = Purpose::where('status', 'Активная')->where('user_id', Auth::id())->get();
        $purposeClose = Purpose::where('status', 'Закрытая')->where('user_id', Auth::id())->get();
        $purposeLose = Purpose::where('status', 'Невыполненная')->where('user_id', Auth::id())->get();
        $pAcount = Purpose::selectRaw('COUNT(purposes.id) as count')->where('status', 'Активная')->where('user_id', Auth::id())->first();
        $pCcount = Purpose::selectRaw('COUNT(purposes.id) as count')->where('status', 'Закрытая')->where('user_id', Auth::id())->first();
        $pLcount = Purpose::selectRaw('COUNT(purposes.id) as count')->where('status', 'Невыполненная')->where('user_id', Auth::id())->first();
        // блок финансов
        $sum = Finance::where('user_id', Auth::id())->selectRaw('SUM(balance) as sum')->first();
        $allFinance = Finance::where('user_id', Auth::id())->get();
        foreach ($allFinance as $a) {
            $i = $a->balance / ($sum->sum / 100);
            Finance::where('name', $a->name)->update([
                'percent' => $i
            ]);
        }
        $transToBill= Transaction::select('transactions.date as date', 'transactions.sum as sum', 'transactions.status as status', 'transactions.finance_id as fId', 'transactions.id as Id', 'finances.name as name')
        ->join('finances', 'finances.id', 'transactions.finance_id')
        ->where('date','>', $treeDaysAgo)
        ->get();
        $fin = Finance::where('user_id', Auth::id())->get();
        //чисто профиль
        $user = User::where('id', Auth::id())->first();

        return view('profile', [
            'today'=>$today,
            'user'=>$user,
            'pA'=> $purposeActive,
            'pC'=> $purposeClose,
            'pL'=> $purposeLose,
            'pAc'=> $pAcount,
            'pCc'=> $pCcount,
            'pLc'=> $pLcount,
            'fin'=>$fin,
            'trans'=>$transToBill,
            'sum'=>$sum
        ]);
    }
    //редактирование профиля
    public function changeUser(Request $r)
    {
      
        $validator = Validator::make($r->all(), [
            'fio' => 'string|nullable',
            'age' => 'date|nullable',
            'file' => 'image|nullable',
            'color' => 'string|nullable',
        ]);
        if ($validator->fails()) {
            // dd($validator->errors());
            return response()->json($validator->errors(), 400 );
        }
        if (isset($r->file)) {
            $photo = $r->file->store('img', 'public');
            User::where('id', Auth::id())->update([ 'photo'=>$photo,]);
        }

        User::where('id', Auth::id())->update([
            'fio'=>$r->fio,
            'bithday'=>$r->age,
            'color'=>$r->color,
        ]);
        return redirect()->back();
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
                'monthValue'=>$monthValue,
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
            'monthValue'=>$monthValue,
            'goodDays' => $goodDays,
            'normalDays' => $normalDays,
            'badDays' => $badDays,
            'nonDays' => $nonDays,
            'warningValue' => $vals,
            'errors' => 'Error'
        ], 400);

    }
}
