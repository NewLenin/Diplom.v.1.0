@extends('layouts.main')
@section('main')

    <section class="finance">
        <div class="main-container">
            <div class="main-row">
                <h1>Мои финансы</h1>
            </div>
                <div class="main-row">
                    <div class="balance flex-box -wrap -just-center" style="background-color: {{ $color->color }}">
                        <div class="decoration">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                            </svg>
                        </div>
                        <div class="diagram -colum-50">
                            <svg width="90%" height="90%" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21" cy="21" r="15.9154" fill="white" />
                                <circle cx="21" cy="21" r="15.9154" fill="transparent" stroke="#FF5C00"
                                    stroke-width="9" />
                                <circle cx="21" cy="21" r="15.9154" fill="transparent" stroke="#2BBD28"
                                    stroke-width="9" stroke-dasharray="75" />

                            </svg>
                        </div>
                        <div class="balance-info -colum-50">
                            @if ($fin && !$fin->isEmpty())
                            <h3>Всего доступно: <br>{{ $sum->sum }} руб.</h3>
                            @foreach ($fin as $f)
                                <div class="balance-data">
                                    <div class="percent">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12.5" cy="12.5" r="12.5" id="{{$f->id}}"
                                                fill="{{ $f->color }}" />
                                        </svg>
                                        <p>{{ number_format($f->percent, 2, '.', ' ') }}%</p>
                                    </div>
                                    <div class="account-balance">
                                        <p>{{ $f->name }}</p>
                                        <h4>{{ $f->balance }} руб.</h4>
                                    </div>
                                </div>
                            @endforeach
                            @else
                            <h1>Счета отсутствуют</h1>
                            @endif
                        </div>

                    </div>
                </div>
        <div class="main-row flex-box -just-between -center">
            <h1>Мои счета</h1>
            <button class="add-invoice" data-bs-toggle="modal" data-bs-target="#addBill">Добавить счёт</button>
        </div>
        <div class="modal fade " id="addBill" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="exampleModalLabel">Новый счёт</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addBill') }}" method="post" id="addBillForm"
                            onsubmit="formAction(this, event)">
                            <input type="text" name="name" placeholder="Название счёта" id="nameInput"
                                class="form-control mb-2">
                            <div class="invalid-feedback" id="nameError"></div>
                            <input type="color" name="color" id="colorInput" class=" mb-2">
                            <label for="colorInput">Цвет счёта</label>
                            <div class="invalid-feedback" id="colorError"></div>
                            <input type="number" name="balance" min="1" step="any"
                                placeholder="Начальный капитал" id="balanceInput" class="form-control mb-2">
                            <div class="invalid-feedback" id="balanceError"></div>
                            <div class="alert alert-danger mt-3" style="display: none" id="formError"
                                role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-row flex-box -just-center -wrap">
            @if ($fin && !$fin->isEmpty())
                @foreach ($fin as $f)
                    <div class="invoice" style="background-color: {{ $color->color }}">
                        <div class="decoration">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                            </svg>
                        </div>
                        <div class="invoice-header">
                            <form action="" method="post">
                                <div class="invoice-container">
                                    <div class="invoice-name">
                                        {{ $f->name }}
                                    </div>
                                    <div class="invoice-color">
                                        <input type="color" name="invoice-color-input" class="invoice-color-input{{$f->id}}" id="{{$f->id}}" value="{{ $f->color }}">
                                    </div>
                                </div>

                                <a href="{{ route('deleteBill', $f->id) }}"
                                    onclick="return confirm('Вы уверены?'); return false" class="delete"> Удалить</a>
                            </form>

                        </div>
                        <div class="invoice-content flex-box -just-center -wrap">
                            <div class="-colum-50 invoice-balance">
                                <h3>Всего доступно: <br>{{ $f->balance }}руб.</h3>
                                <div class="buttons">
                                    <button class="deposit" data-bs-toggle="modal"
                                        data-bs-target="#plusMoney{{ $f->id }}">Пополнить</button>
                                    <button class="deduct" data-bs-toggle="modal"
                                        data-bs-target="#minusMoney{{ $f->id }}">Списать</button>
                                </div>
                                <hr>
                            </div>

                            <div class="-colum-50 invoice-change-balance">

                                <h2>Последние пополнения:</h2>
                                @foreach ($transToBill as $t)
                                @if ($t->fId == $f->id)
                                <p>@if ($t->status == 1)+{{ $t->sum }} руб. @endif</p>
                                @endif
                                @endforeach

                                <h2>Последние списания:</h2>
                                @foreach ($transToBill as $t)
                                @if ($t->fId == $f->id)
                                <p>@if ($t->status == 0)-{{ $t->sum }} руб. @endif</p>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <div class="modal fade " id="plusMoney{{ $f->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title fs-5" id="exampleModalLabel">Пополнить счёт</h2>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('deposit') }}" method="post" id="depositForm"
                                        onsubmit="formAction(this, event)">
                                        <input type="text" name="id"
                                            value="{{ $f->id }}"class="visually-hidden">
                                        <input type="text" name="balance"
                                            value="{{ $f->balance }}"class="visually-hidden">
                                        <input type="number" name="sum" min="1" step="any"
                                            placeholder="Сколько вы хотите положить на счёт" id="sumInput"
                                            class="form-control mb-2">
                                        <div class="invalid-feedback" id="sumError"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Закрыть</button>
                                    <button type="submit" class="btn btn-success">Пополнить</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade " id="minusMoney{{ $f->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title fs-5" id="exampleModalLabel">Списать со счёта</h2>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('deduct') }}" method="post" id="deductForm"
                                        onsubmit="formAction(this, event)">
                                        <input type="text" name="id"
                                            value="{{ $f->id }}"class="visually-hidden">
                                        <input type="text" name="balance"
                                            value="{{ $f->balance }}"class="visually-hidden">
                                        <input type="number" name="sum" min="1" step="any"
                                            placeholder="Сколько вы хотите снять со счёта" id="sunInput"
                                            class="form-control mb-2">
                                        <div class="invalid-feedback" id="sumError"></div>
                                        <div class="alert alert-danger mt-3" style="display: none" id="formError{{ $f->id }}"
                                            role="alert"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Закрыть</button>
                                    <button type="submit" class="btn btn-success">Списать</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h1>Счета отсутсвутют</h1>
            @endif
        </div>

        <div class="main-row">
            <h1>Транзакции по счетам</h1>
        </div>
        <div class="main-row">
            <div class="transaction" style="background-color: {{ $color->color }}">
                <div class="decoration">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12.5" cy="12.5" r="12.5" fill="white" />
                    </svg>
                </div>
                <div class="transaction-content">
                    @if (
                        ($transToday && !$transToday->isEmpty()) ||
                            ($transYest && !$transYest->isEmpty()) ||
                            ($transYest && !$transYest->isEmpty()))
                        @if ($transToday && !$transToday->isEmpty())
                            <h2>Сегодня</h2>
                            @foreach ($transToday as $t)
                                <div class="info" style="background-color: {{ $color->color }}">{{ $t->name }}: @if ($t->status == 0)-@else+@endif{{ $t->sum }} руб.</div>
                            @endforeach
                        @endif
                        @if ($transYest && !$transYest->isEmpty())
                            <h2>Вчера</h2>
                            @foreach ($transYest as $t)
                                <div class="info" style="background-color: {{ $color->color }}">{{ $t->name }}: @if ($t->status == 0)-@else+@endif{{ $t->sum }} руб.</div>
                            @endforeach
                        @endif
                        @if ($transYest && !$transYest->isEmpty())
                            <h2>Более 3 дней назад</h2>
                            @foreach ($transAgo as $t)
                                <div class="info" style="background-color: {{ $color->color }}">{{ $t->name }}: @if ($t->status == 0)- @else+@endif{{ $t->sum }} руб.</div>
                            @endforeach
                        @endif
                    @else
                        <h1>Транзакций ещё не производилось</h1>
                    @endif
                </div>
            </div>
        </div>

        </div>
    </section>






@endsection
