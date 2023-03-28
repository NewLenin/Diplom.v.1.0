<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Purpose;
use App\Models\User;
use App\Models\PurposeTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PurposeController extends Controller
{
    public function Index()
    {
        //можно сделать проверку на выполнение всех задача но хз
        //Проверка по дате, автоматически кидает в невыполненные если дата выполнения превышена
        $time = Carbon::today('Asia/Yekaterinburg');
        $allPurp = Purpose::where('purposes.status', 'Активная')->get();
        foreach ($allPurp as $a) {
            if (strtotime($a->dating) < strtotime($time)) {
                Purpose::where('dating', $a->dating)->update([
                    'status'=> 'Невыполненная'
                ]);
            }
        }

         //Цвет профиля
        $color = User::where('id', Auth::id())->first();

        $activePurposes = Purpose::where('purposes.status', 'Активная')
            ->where('user_id', Auth::user()->id)
            ->select('purposes.name as purpose', 'purposes.dating as dating', 'purposes.id as id')
            ->orderBy('purposes.created_at', 'desc')
            ->get();
        $activeTasks = PurposeTask::where('purposes.status', 'Активная')
            ->select('purpose_tasks.name as name', 'purpose_tasks.status as status', 'purpose_tasks.id as id', 'purpose_tasks.purpose_id as pId')
            ->join('purposes', 'purpose_tasks.purpose_id', 'purposes.id')
            ->get();

        $losePurposes = Purpose::where('purposes.status', 'Невыполненная')
            ->where('user_id', Auth::user()->id)
            ->select('purposes.name as purpose', 'purposes.dating as dating', 'purposes.id as id')
            ->orderBy('purposes.created_at', 'desc')
            ->get();
        $loseTasks = PurposeTask::where('purposes.status', 'Невыполненная')
            ->select('purpose_tasks.name as name', 'purpose_tasks.status as status', 'purpose_tasks.id as id', 'purpose_tasks.purpose_id as pId')
            ->join('purposes', 'purpose_tasks.purpose_id', 'purposes.id')
            ->get();

        return view('purpose', [
            'activePurposes' => $activePurposes,
            'activeTasks' => $activeTasks,
            'losePurposes' => $losePurposes,
            'loseTasks' => $loseTasks,
            'color'=>$color
        ]);
        // return dd($activeTasks, $activePurposes);
    }
     //Добавление цели
    public function addPurpose(Request $r)
    {
        $time = Carbon::today('Asia/Yekaterinburg');
        $rcount = count($r->all()) - 3;
        $validator = Validator::make($r->all(), [
            'purpose' => 'required|string',
            'date' => 'required|date|',
            '0' => 'required|string|'
        ]);

        $purpLoseName = Purpose::where('user_id', Auth::id())->where('name',$r->purpose)->where('status', 'Активная')->first();
        $purpActiveName = Purpose::where('user_id', Auth::id())->where('name',$r->purpose)->where('status', 'Невыполненная')->first();
        if (!is_null($purpActiveName)) {
            return response()->json(['errors' => ['form' => 'У вас уже есть цель с таким именем']], 400);
        }
        if (!is_null($purpLoseName)) {
            return response()->json(['errors' => ['form' => 'У вас уже есть цель с таким именем']], 400);
        }

        // dd($r->all(), $validator);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (strtotime($r->date) > strtotime($time)) {
            $purpose = Purpose::create([
                'user_id' => Auth::user()->id,
                'name' => $r->purpose,
                'dating' => $r->date,
                'status' => 'Активная'
            ]);
              //Костыль из-за ломаного валидатора
            $t = 0;
            PurposeTask::create([
                'purpose_id' => $purpose->id,
                'name' => $r->$t,
                'status' => 0
            ]);

            for ($i = 2; $i <= $rcount; $i++) {
                if ($r->$i != null) {
                    PurposeTask::create([
                        'purpose_id' => $purpose->id,
                        'name' => $r->$i,
                        'status' => 0
                    ]);
                }

            }
            return response()->json(['purpose' => 'success'], 200);
        }else {
            return response()->json(['errors' => ['form' => 'Дата должна быть поставлена на будущее']], 400);
        }

    }
      //Удаление цели
    public function deletePurpose($id)
    {
        Purpose::where('id', $id)->delete();
        return redirect()->route('purpose');

    }
      //Возвращение из невыполненных целей в активные
    public function changePurpose(Request $r)
    {
        $time = Carbon::today('Asia/Yekaterinburg');
        if (strtotime($r->date) > strtotime($time)) {
        Purpose::where('id', $r->id)->update([
            'dating' => $r->date,
            'status' => 'Активная'
        ]);
        return response()->json(['purpose' => 'success'], 200);
    }
        else {
            return response()->json([$r->id, 'errors' => ['form' => 'Дата должна быть поставлена на будущее']], 400);
        }
    }

      //Закрытие цели
    public function closePurpose(Request $r)
    {
        $tasks = PurposeTask::where('purpose_id', $r->purpose_id)->get();
        $status = 1;
        foreach ($tasks as $t) {
            if ($t->status == 0) {
                $status = 0;
            }
        }
        if ($status == 0) {
            return response()->json([$r->purpose_id, 'errors' => ['form' => 'Вы не выполнили все поставленные задачи!']], 400);
        } else {
            Purpose::where('id', $r->purpose_id)->update([
                'status' => 'Закрытая'
            ]);
            return response()->json(['purpose' => 'success'], 200);
        }

    }

      //Изменение статуса задачи
    public function changeTask(Request $r)
    {
        $task = PurposeTask::where('id', $r->id)->first();

            if ($task->status == 0) {
                PurposeTask::where('id', $r->id)->update([
                    'status' => 1
                ]);
                $status = 1;
            } else {
                PurposeTask::where('id', $r->id)->update([
                    'status' => 0
                ]);
                $status = 0;

            }
            return response()->json(['success' => $status], 200);
        }

    }


