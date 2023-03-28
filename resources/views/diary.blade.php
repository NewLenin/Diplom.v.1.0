@extends('layouts.main')
@section('main')
<section class="diary">
    <div class="main-container">
        <form action="{{route('addNote')}}" method="post" id="addNote" onsubmit="formAction(this, event)">
        <div class="main-row flex-box -wrap -just-between">
            <div class="diary-form -colum-50" style="border-color: {{$color->color}}">
                <label for="textarea">Запись от <span id="date-info"></span></label>
                <textarea name="message" id="messageInput" cols="30" rows="10"  style="border-color: {{$color->color}}">
                </textarea>
                <div class="invalid-feedback" id="messageError"> </div>
                <div class="button">
                    <button type="submit" style="border-color: {{$color->color}}">Записать</button>
                </div>
            </div>

            <div class="-colum-50">
                <div class="calendar" style="background-color: {{$color->color}}">
                    <div class="calendar-decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <div class="calendar-content">
                        <input type="date" name="date" id="calendar-diary" value="{{$today}}" class="calendar-input">
                    </div>
                </div>
                <div class="diary-statistic" style="background-color: {{$color->color}}">
                    <label for="diary-statistic-content">Статистика за <span id="monthValue"></span></label>
                    <div class="diary-statistic-content">
                        <div class="diary-days">
                            <h1>Хороших дней:<span id="text-success" class="text-success"></span></h1>
                            <h1>Нормальных дней:<span id="text-warning" class="text-warning"></span></h1>
                            <h1>Плохих дней:<span id="text-danger" class="text-danger"></span></h1>
                            <h1>Дней без указания статуса:<span id="text-primary" class="text-primary"></span></h1>
                        </div>
                        <div class="diary-warning">
                            <h1>Главная причина переживаний в этом месяце: <span id="warningValue" class="text-danger"></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-row">
            <div class="additional-parameters">
                <div class="day-status">
                    <input type="checkbox" name="day-status-checkbox" id="day-status-checkbox" class="day-status-checkbox">
                    <label for="day-status-checkbox">Отмечать каким был день</label>
                    <select class="form-control mb-2"  style="display: none"  name="status" id="status-select" >
                        <option  value="">Выберите каким был день</option>
                        <option  value="Отличный">Отличный</option>
                        <option  value="Нормальный">Нормальный</option>
                        <option  value="Плохой">Плохой</option>
                      </select>
                    <input type="checkbox" name="day-status-checkbox"id="day-warning-checkbox" class="day-warning-checkbox">
                    <label for="day-warning-checkbox">Отмечать причину переживаний</label>

                </div>
                <input  type="text" name="warning" id="warning" placeholder="Причина переживаний" style="display: none" class="form-control mb-2">
            </div>
        </div>
    </form>
    </div>
</section>
@endsection
