@extends('layouts.main')
@section('main')
    <section class="purpose">
        <div class="main-container">
        <div class="main-row">
            <h1>Активные цели</h1>
        </div>
        <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
              <!-- Slides -->
              @if ($activePurposes && !$activePurposes->isEmpty())
              @foreach ($activePurposes as $ap)
                  <div class="slider-element swiper-slide " style="background-color: {{$color->color}}">
                      <div class="element-header">
                          <h2>{{ $ap->purpose }}</h2>
                          <div class="date">Цель до {{ $ap->dating }}</div>
                      </div>
                      <div class="element-content">
                          <form action="{{route('closePurpose')}}" method="post" id="closePurposeForm" onsubmit="formAction(this, event)">
                              @csrf
                              <input type="text" name="purpose_id" value="{{ $ap->id }}">
                              <div class="tasks">
                              @foreach ($activeTasks as $at)
                              @if ($at->pId == $ap->id)

                                  <input type="text" name="task_id" value="{{ $at->id }}"
                                      class="visually-hidden">
                                  <input type="checkbox"  value="{{$at->id}}"
                                      id="task{{ $at->id }}-status-checkbox{{ $at->id }}"
                                      class="task-status-checkbox" @if ($at->status == 1) checked @endif>
                                  <label
                                      for="task{{ $at->id }}-status-checkbox{{ $at->id }}">{{ $at->name }}</label>

                                      @endif
                              @endforeach
                          </div>
                              <div class="flex-box -just-center">
                                  <button type="submit" class="submit">Закрыть цель</button>
                              </div>
                              <div class="alert alert-danger mt-3" style="display: none" id="formError{{$ap->id}}" role="alert"></div>

                          </form>
                      </div>
                  </div>
              @endforeach
          @else
              <h1>Активных целей нет</h1>
          @endif

            </div>


            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev">
                <svg width="0" height="0" viewBox="0 0 36 79" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M34 2L3 38.9048L34 77" stroke="black" stroke-width="4" />
                </svg>
            </div>
            <div class="swiper-button-next">
                <svg width="0" height="0" viewBox="0 0 36 79" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M2 77L33 40.0952L2 2" stroke="black" stroke-width="4" />
            </svg>
            </div>


          </div>


        </div>

        <div class="main-container">
            <div class="main-row">
                <h1>
                    Добавить новую цель
                </h1>
            </div>
            <div class="main-row">
                <div class="add-purpose" style="background-color: {{$color->color}}">
                    <div class="decoration">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                        </svg>
                    </div>
                    <form id="addpurposeForm" method="post" action="{{route('addPurpose')}}" onsubmit="formAction(this, event)">
                        @csrf
                        <div class="add-purpose-header">
                            <div class="d-flex flex-column input-cont">
                            <input type="text" name="purpose" placeholder="Цель" id="purposeInput">
                            <div class="invalid-feedback bg-white rounded p-1 " id="purposeError"></div>
                        </div>
                        <div class="d-flex flex-column input-cont">
                            <input type="date" name="date" id="dateInput">
                            <div class="invalid-feedback bg-white rounded p-1" id="dateError"> </div>

                        </div>
                        </div>
                        <div class="alert alert-danger mt-3" style="display: none" id="formError" role="alert"></div>
                        <div class="add-purpose-content">
                            <input type="text" name="0" id="0Input" class="task" placeholder="Задача 1">
                            <div class="invalid-feedback"  id="0Error"></div>
                            <button type="button" class="add-task" onclick="addEl()">
                                <svg width="47" height="47" viewBox="0 0 47 47" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="23.5" cy="23.5" r="22.5" fill="white" stroke="#42FF00"
                                        stroke-width="2" />
                                    <path d="M10.2546 23.9272H36.7455" stroke="#42FF00" stroke-width="2" />
                                    <path d="M23.5 36.7454V10.2545" stroke="#42FF00" stroke-width="2" />
                                </svg>
                                Добавить новую задачу
                            </button>
                            <div class="flex-box -just-center">
                                <button type="submit" class="submit">Добавить цель</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="main-row">
                @if ($losePurposes && !$losePurposes->isEmpty())
                    <h1>
                        Невыполенные цели
                    </h1>
            </div>
            <div class="main-row flex-box -wrap -just-center">

                @foreach ($losePurposes as $lp)
                    <div class="unfulfilled-purpose -colum-50" style="background-color: {{$color->color}}">
                        <form action="{{route('changePurpose')}}" method="post" id="changePurposeForm"  onsubmit="formAction(this, event)">
                            @csrf
                            <div class="unfulfilled-header">
                                <h2>{{ $lp->purpose }}</h2>
                                <input type="date" name="date" class="date" value="{{$lp->dating}}">
                                <input type="text" name="id" value="{{ $lp->id }}"
                                    class="visually-hidden">
                            </div>
                            <div class="unfulfilled-content">
                                @foreach ($loseTasks as $lt)
                                @if ($lt->pId == $lp->id)
                                    <input type="checkbox" id="unfulfilled2-status-checkbox"
                                        class="unfulfilled-status-checkbox" @if ($lt->status == 1) checked @endif>
                                    <label for="unfulfilled-status-checkbox">{{ $lt->name }}</label>
                                    @endif
                                @endforeach
                                <div class="flex-box -just-between">
                                    <button type="submit" class="again">Начать снова</button>
                                    <a href="{{ route('deletePurpose', $lp->id) }}"
                                        onclick="return confirm('Вы уверены?'); return false" class="delete"> Удалить</a>
                                </div>
                                <div class="alert alert-danger mt-3" style="display: none" id="formError{{$lp->id}}" role="alert"></div>
                            </div>
                        </form>
                    </div>
                @endforeach
            @else
                <h1>Невыполенных целей нет</h1>
                @endif

            </div>



        </div>

    </section>

@endsection
