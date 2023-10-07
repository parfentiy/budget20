@props([
        'date_start' => '',
        'date_end' => '',
        'source_accs' => [],
        'dest_accs' => [],
        'amount_min' => '',
        'amount_max' => '',
        'is_set' => '',
        'sortType' => '',
        'sortItems' => '',

])

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap.bundle.min.js"></script>
    <style>
        form {
            margin: 1; /* Убираем отступы */
        }
    </style>
</head>

<x-header/>
@include('layouts.reports')
<div class="d-flex flex-row justify-content-center text-align-top">
<div  class="mx-9">
    <h4 class="fw-weight-bolder align-top text-center">Журнал проводок</h4>
        <div class="d-flex flex-row justify-content-center">
            <table class="table table-bordered table-striped table-hover table-sm caption-top align-top">
            <thead class="table-light text-center align-top">
                <tr>
                    <th scope="col">Дата операции</th>
                    <th scope="col">Счет-источник</th>
                    <th scope="col">Счет зачисления</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Описание</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody class="text-center">
            @foreach ($transactions as $key => $transaction)
                <tr>
                    <form class="" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('transaction.delete')}}">
                    @csrf
                        <td>
                            {{date("d.m.Y", strtotime($transaction->operation_date))}}
                        </td>
                        <td>
                            {{\App\Models\Account::find($transaction->source_account_id)->name}}
                        </td>
                        <td>
                            {{\App\Models\Account::find($transaction->dest_account_id)->name}}
                        </td>
                        <td>
                            {{$transaction->amount}} р.
                        </td>
                        <td class="text-left">
                            {{$transaction->description}}
                        </td>
                        <td class="col-1 align-top">
                            <button type="submit" class="btn btn-danger btn-sm" name="id" value="{{$transaction->id}}"  onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                        </td>
                    </form>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <td colspan="3"  class="text-center">
                    <h4>Итого</h4>
                </td>
                <td class="text-center">
                    <h4>{{$amount_sum}} р.</h4>
                </td>
                <td colspan="2">
                </td>
            </tfoot>

            </table>

        </div>
    </div>

    <div class="mx-9">
        <div>
            <h4 class="text-center">Фильтры</h4>
            <form method="POST" action="{{ route('post.reports.transactions') }}">
                @csrf
                <div class="d-flex flex-column  justify-content-center">
                    <input hidden type="number" name="is_set" value="1"/>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-2">Начальная дата</label>
                            <input class="form-control form-control-sm" type="date" name="date_start" value="{{$date_start ?? now()}}"/>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-2">Конечная дата</label>
                            <input class="form-control form-control-sm" type="date" name="date_end" value="{{$date_end ?? now()}}"/>
                        </div>
                    </div>
                    <div class="d-flex flex-column m-3 text-center justify-content-top">
                        <div>
                            <label> </label>
                            <div><button type="submit" class="btn btn-primary btn-sm" name="action" value="filterOnMonth">Текущий месяц</button> </div>
                        </div>
                    </div>
                    <div class="d-flex fw-bold pt-2 flex-column mx-3 justify-content-top">
                        <div>
                            <label>Источники</label>
                            <select name="source_accs[]" multiple class="form-select form-select-sm">
                                @foreach($source_accs as $source_acc)
                                    <option value="{{$source_acc}}">{{\App\Models\Account::find($source_acc)->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex fw-bold pt-2 flex-column mx-3 justify-content-top">
                        <div>
                            <label>Зачисления</label>
                            <select name="dest_accs[]" multiple class="form-select form-select-sm">
                                @foreach($dest_accs as $dest_acc)
                                    <option value="{{$dest_acc}}">{{\App\Models\Account::find($dest_acc)->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-2">Мин. сумма</label>
                            <input class="form-control form-control-sm" type="number" name="amount_min" value="{{$amount_min ?? 0}}"/>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-2">Макс. сумма</label>
                            <input class="form-control form-control-sm" type="number" name="amount_max" value="{{$amount_max ?? 0}}"/>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-4">Сортировка по:</label>
                            <select name="sort_item" class="form-select form-select-sm">
                                @foreach($sort_items as $key => $item)
                                    @if ($key == $sort_item)
                                        <option selected value="{{$key}}">{{$item}}</option>
                                    @else
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-top">
                        <div>
                            <label class="fw-bold pt-2">Порядок сортировки:</label>
                            <select name="sort_type" class="form-select form-select-sm">

                                @foreach($sort_types as $key => $type)
                                    @if ($key == $sort_type)
                                        <option selected value="{{$key}}">{{$type}}</option>
                                    @else
                                        <option value="{{$key}}">{{$type}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column mx-3 justify-content-center text-center py-2">
                        <div >
                            <label> </label>
                            <div><button type="submit" class="btn btn-primary btn-sm" name="" value="">Применить</button> </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="d-flex flex-row my-3 justify-content-center">
                <form method="POST" action="{{ route('post.reports.transactions') }}">
                @csrf
                <div class="d-flex flex-row justify-content-center">

                        <div>
                            <label> </label>
                            <div><button type="submit" class="btn btn-primary btn-sm" name="is_set" value="0">Сбросить фильтры</button> </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




