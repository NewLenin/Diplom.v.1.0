<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    public function Index()
    {
        $sum = Finance::where('user_id', Auth::id())->selectRaw('SUM(balance) as sum')->first();
        $allFinance = Finance::where('user_id', Auth::id())->get();
        foreach ($allFinance as $a) {
            $i = $a->balance / ($sum->sum / 100);
            Finance::where('name', $a->name)->update([
                'percent' => $i
            ]);
        }

        $today = Carbon::today('Asia/Yekaterinburg');
        $yesterday = Carbon::yesterday('Asia/Yekaterinburg');
        $treeDaysAgo = Carbon::today('Asia/Yekaterinburg')->subDays(3);
        //Цвет профиля
        $color = User::where('id', Auth::id())->first();
        $fin = Finance::where('user_id', Auth::id())->get();

        $transToBill= Transaction::select('transactions.date as date', 'transactions.sum as sum', 'transactions.status as status', 'transactions.finance_id as fId', 'transactions.id as Id', 'finances.name as name', 'finances.user_id as user_id')
        ->join('finances', 'finances.id', 'transactions.finance_id')
        ->where('finances.user_id', Auth::id())
        ->where('date','>', $treeDaysAgo)
        ->get();

        $transToday = Transaction::select('transactions.date as date', 'transactions.sum as sum', 'transactions.status as status', 'finances.name as name', 'finances.user_id as user_id')
        ->join('finances', 'finances.id', 'transactions.finance_id')
        ->where('finances.user_id', Auth::id())
        ->where('date', $today)
        ->get();

        $transYest = Transaction::select('transactions.date as date', 'transactions.sum as sum', 'transactions.status as status', 'finances.name as name', 'finances.user_id as user_id')
        ->join('finances', 'finances.id', 'transactions.finance_id')
        ->where('finances.user_id', Auth::id())
        ->where('date', $yesterday)
        ->get();

        $transAgo = Transaction::select('transactions.date as date', 'transactions.sum as sum', 'transactions.status as status', 'finances.name as name', 'finances.user_id as user_id')
        ->join('finances', 'finances.id', 'transactions.finance_id')
        ->where('finances.user_id', Auth::id())
        ->where('date','<', $treeDaysAgo)
        ->get();

        // dd($transToday, $transYest);
        return view('finance', [
        'fin' => $fin,
            'sum' => $sum,
            'color'=>$color,
            'transToday'=>$transToday,
            'transYest'=> $transYest,
            'transAgo' =>$transAgo,
            'transToBill'=> $transToBill
        ]);
    }


    public function addBill(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required|string',
            'color' => 'required|string',
            'balance' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $finName = Finance::where('user_id', Auth::id())->where('name',$r->name)->first();
        if (!is_null($finName)) {
            return response()->json(['errors' => ['form' => 'У вас уже есть счёт с таким именем']], 400);
        }

        $colorName = Finance::where('user_id', Auth::id())->where('color',$r->color)->first();
        if (!is_null($colorName)) {
            return response()->json(['errors' => ['form' => 'У вас уже есть счёт с таким цветом']], 400);
        }
            Finance::create([
                'user_id' => Auth::id(),
                'name' => $r->name,
                'color' => $r->color,
                'percent' => 1,
                'balance' => $r->balance
            ]);

        return response()->json(['finance' => 'success'], 200);
    }

    public function deposit(Request $r)
    {
        $today = Carbon::today('Asia/Yekaterinburg');
        $validator = Validator::make($r->all(), [
            'sum' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        Finance::where('id', $r->id)->update([
            'balance'=>$r->balance + $r->sum
        ]);
        Transaction::create([
            'finance_id'=>$r->id,
            'user_id'=>Auth::id(),
            'sum'=>$r->sum,
            'date'=>$today,
            'status'=>1
        ]);

        return response()->json(['finance' => 'success'], 200);

    }
    public function deduct(Request $r)
    {
        $today = Carbon::today('Asia/Yekaterinburg');
        $validator = Validator::make($r->all(), [
            'sum' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if ($r->balance >= $r->sum) {
            Finance::where('id', $r->id)->update([
                'balance'=>$r->balance - $r->sum
            ]);
            Transaction::create([
                'finance_id'=>$r->id,
                'user_id'=>Auth::id(),
                'sum'=>$r->sum,
                'date'=>$today,
                'status'=>0
            ]);
            return response()->json(['finance' => 'success'], 200);
        }else {
            return response()->json([$r->id, 'errors' => ['form' => 'Сумма списания не может превышать баланс']], 400);
        }

    }
    public function deleteBill($id)
    {
        Finance::where('id', $id)->delete();
        return redirect()->route('finance');
    }
    public function changeColor(Request $r)
    {
        Finance::where('id', $r->id)->update([
            'color'=>$r->color
        ]);
        return response()->json(['finance' => 'success'], 200);
    }
}
