@php
    $months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
@endphp
<x-header/>
<x-app-layout/>

@php
    if(isset($budgetId)) {
        $currentBudget = \App\Models\PlanBudget::whereId($budgetId)->first();
        $currentBudgetItems = json_decode(\App\Models\PlanBudget::whereId($budgetId)->pluck('dataset')->first(), true);
        $currentBudgetIncomes = json_decode(\App\Models\PlanBudget::whereId($budgetId)->pluck('incomes')->first(), true);

        usort($currentBudgetItems, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

    }
@endphp

<div>
    <div class="d-flex flex-row justify-content-center">
        <h2>Планирование бюджета</h2>
    </div>
    <div class="d-flex flex-row justify-content-center">
        <div class="d-flex flex-column mx-2 my-2 align-items-center">
            <div class="d-flex flex-row text-center fw-bold">
                Список бюджетов
            </div>

            <div class="d-flex flex-row justify-content-center">
                <table class="table table-sm w-auto table-bordered table-striped table-hover caption-top align-top">
                    <thead class="table-light text-center align-top">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Месяц</th>
                        <th scope="col">Год</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <tr>
                        <form class="" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.add')}}">
                        @csrf
                            <td>
                            </td>
                            <td>
                                <select name="month" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg">
                                    @foreach($months as $key => $month)
                                        <option value="{{$key + 1}}">{{$month}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <x-text-input id="year" min="1980" max="2500" pattern="\d{4}" maxlength="4" class="form-control form-control-sm block mt-1 items-center justify-center" type="number" name="year" required autofocus autocomplete="year" />
                                <x-input-error :messages="$errors->get('year')" class="mt-2" />
                            </td>
                            <td class="align-top">
                                <button type="submit" class="btn btn-success btn-sm" name="id" value="">Создать</button>
                            </td>
                            <td class="align-top">
                                <select name="cloneFrom" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg">
                                    @foreach (\App\Models\PlanBudget::where('user_id', Auth::user()->id)->orderBy('year')->orderBy('month')->get() as $key => $planBudget)
                                        <option value="{{$planBudget->id}}">{{$months[$planBudget->month - 1]}} {{$planBudget->year}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm" name="isClone" value="true">Клонировать</button>
                            </td>
                        </form>

                    </tr>
                    @foreach (\App\Models\PlanBudget::where('user_id', Auth::user()->id)->orderBy('year')->orderBy('month')->get() as $key => $planBudget)

                        <tr>
                            <form class="" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.edit')}}">
                                @csrf
                                <input type="hidden" name="budgetId" value="{{$planBudget->id}}">
                                <td>
                                    {{$key + 1}}
                                </td>
                                <td>
                                    {{$months[$planBudget->month - 1]}}
                                </td>
                                <td>
                                    {{$planBudget->year}}
                                </td>
                                <td class="align-top">
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete" value="{{$planBudget->id}}" onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                                </td>
                                <td class="align-top">
                                    <button type="submit" class="btn btn-primary btn-sm" name="budgetId" value="{{$planBudget->id}}">Планировать</button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="d-flex flex-column mx-2 my-2 align-items-center">
            @if (!isset($budgetId))
                <div class="d-flex flex-row text-xxl fw-bold" style="width: auto;">Текущий бюджет не выбран</div>
            @else
                <div class="d-flex flex-row text-xxl fs-6 badge bg-primary text-wrap fw-bold" style="width: auto;">Текущий бюджет: {{$months[$currentBudget['month'] - 1]}} - {{$currentBudget['year']}}</div>
            @endif
            <div>
                @if (isset($budgetId))
                    <table class="table table-sm w-auto table-bordered table-striped table-hover align-top">
                        <thead>
                        <tr>
                            <th scope="col">Порядковый номер</th>
                            <th scope="col">Статья</th>
                            <th scope="col">Сумма</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <form id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.addItem')}}">
                                @csrf
                                <td class="text-center">
                                    <x-text-input id="order" class="block mt-1 items-center justify-center form-control form-control-sm" type="text" name="order" required autofocus autocomplete="order" />
                                    <x-input-error :messages="$errors->get('order')" class="mt-2" />
                                </td>
                                <td class="text-center justify-center">
                                    <div class="d-flex flex-row align-items-center">
                                        <select id="sel1" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg" onchange="giveSelection(this.value)">
                                            @foreach(\App\Models\Account::where('user_id', Auth::user()->id)->where('category', 0)->get() as $account)
                                                <option value="{{$account->id}}">{{$account->name}}</option>
                                            @endforeach
                                        </select>
                                        <select id="sel2" name="account_id" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg">
                                            @foreach(\App\Models\Account::where('user_id', Auth::user()->id)->where('category', '!=', 0)->get() as $account)
                                                <option value="{{$account->id}}" data-option="{{$account->category}}">{{$account->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <x-text-input id="sum" class="block mt-1 items-center justify-center form-control form-control-sm" type="number" name="sum" required autofocus autocomplete="sum" />
                                    <x-input-error :messages="$errors->get('sum')" class="mt-2" />
                                </td>
                                <td class="col-1 align-top">
                                    <button type="submit" class="btn btn-success btn-sm" name="currentBudget" value="{{$currentBudget->id}}">Добавить</button>
                                </td>
                            </form>
                        </tr>
                        @foreach($currentBudgetItems as $item)
                            @php
                                $wasted = \App\Models\CashFlow::where('user_id', Auth::user()->id)
                                    ->where('dest_account_id', $item['account'])
                                    ->whereMonth('operation_date', $currentBudget['month'])
                                    ->whereYear('operation_date', $currentBudget['year'])
                                    ->pluck('amount')
                                    ->sum();
                            @endphp

                            <tr>
                                <form id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.deleteItem')}}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$item['order']}}">
                                    <td  class="text-center">
                                        {{$item['order']}}
                                    </td>
                                    <td>
                                        {{\App\Models\Account::find($item['account'])->name}}
                                    </td>
                                    <td class="text-center">
                                        <div title="Потраченная сумма {{$wasted}} р.">{{$item['sum']}} р.</div>

                                    </td>
                                    <td class="col-1 align-top text-center">
                                        <button type="submit" class="btn btn-danger btn-sm" name="currentBudget" value="{{$currentBudget->id}}" onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div class="d-flex flex-column mx-2 my-2 align-items-center">
            <div class="d-flex flex-row text-center fw-bold">
                @if (isset($budgetId))
                    Планируемые доходы
                @endif
            </div>
            <div>
                @if (isset($budgetId))
                    <table class="table table-sm table-bordered table-striped table-hover align-top">
                        <thead>
                        <tr>
                            <th scope="col">Статья</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <form id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.addIncome')}}">
                                @csrf
                                <td class="text-center justify-center">
                                    <div class="d-flex flex-row align-items-center">
                                        <select id="sel3" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg" onchange="giveSelection2(this.value)">
                                            @foreach(\App\Models\Account::where('user_id', Auth::user()->id)->where('category', 0)->get() as $account)
                                                <option value="{{$account->id}}">{{$account->name}}</option>
                                            @endforeach
                                        </select>
                                        <select id="sel4" name="account_id" class="form-select form-select-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-lg">
                                            @foreach(\App\Models\Account::where('user_id', Auth::user()->id)->where('category', '!=', 0)->get() as $account)
                                                <option value="{{$account->id}}" data-option="{{$account->category}}">{{$account->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="col-1 align-top">
                                    <button type="submit" class="btn btn-success btn-sm" name="currentBudget" value="{{$currentBudget->id}}">Добавить</button>
                                </td>
                            </form>
                        </tr>
                        @foreach($currentBudgetIncomes as $item)
                            <tr>
                                <form id="category" method="post" enctype="multipart/form-data" action="{{route('planbudget.deleteIncome')}}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$item['account']}}">
                                    <td>
                                        {{\App\Models\Account::find($item['account'])->name}}
                                    </td>
                                    <td class="col-1 align-top text-center">
                                        <button type="submit" class="btn btn-danger btn-sm" name="currentBudget" value="{{$currentBudget->id}}" onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    var sel1 = document.querySelector('#sel1');
    var sel2 = document.querySelector('#sel2');
    var sel3 = document.querySelector('#sel3');
    var sel4 = document.querySelector('#sel4');
    var options2 = sel2.querySelectorAll('option');
    var options4 = sel4.querySelectorAll('option');
    function giveSelection(selValue) {
        sel2.innerHTML = '';
        for(var i = 0; i < options2.length; i++) {
            if(options2[i].dataset.option === selValue) {
                sel2.appendChild(options2[i]);
            }
        }
    }
    function giveSelection2(selValue) {
        sel4.innerHTML = '';
        for(var i = 0; i < options4.length; i++) {
            if(options4[i].dataset.option === selValue) {
                sel4.appendChild(options4[i]);
            }
        }
    }
    giveSelection(sel1.value);
    giveSelection2(sel3.value);
</script>


