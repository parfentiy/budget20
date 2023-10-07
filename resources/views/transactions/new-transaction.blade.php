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
<x-app-layout/>
    <div class="d-flex flex-row justify-content-center mb-7">
        <h2>Новая проводка</h2>
    </div>



    <form method="POST" action="{{ route('transaction.new') }}">
        @csrf

        <div class="d-flex flex-row justify-content-center">
            <x-input-label for="source_account_id" :value="__('Счет списания')" />
        </div>
        <div class="d-flex flex-row justify-content-center">
            <select name="source_account_id" class="w-25 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($subCategories as $key => $subCategory)
                    <option value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="d-flex flex-row justify-content-center">
            <x-input-label for="dest_account_id" :value="__('Счет зачисления')" />
        </div>
        <div class="d-flex flex-row justify-content-center">
            <select name="dest_account_id" class="w-25 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($subCategories as $key => $subCategory)
                    <option value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="d-flex flex-row justify-content-center">
            <x-input-label for="amount" :value="__('Сумма')" />
        </div>
        <div class="d-flex flex-row justify-content-center">
            <x-text-input id="name" class="block mt-1 w-25 items-center justify-center" type="number" name="amount" :value="old('amount')" required autofocus autocomplete="amount" />
            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        </div>

        <div class="d-flex flex-row justify-content-center">
            <x-input-label for="operation_date" :value="__('Дата проводки')" />
        </div>
        <div class="d-flex flex-row justify-content-center">
            <x-text-input id="operation_date" class="block mt-1 w-25 items-center justify-center" type="date" name="operation_date" :value="old('operation_date')" required autofocus autocomplete="operation_date" />
            <x-input-error :messages="$errors->get('operation_date')" class="mt-2" />
        </div>

        <div class="d-flex flex-row justify-content-center">
            <x-input-label for="description" :value="__('Описание')" />
        </div>
        <div class="d-flex flex-row justify-content-center">
            <x-text-input id="description" class="block mt-1 w-25 items-center justify-center" type="text" name="description" :value="old('description')" autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>








        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="ml-4">
                {{ __('Добавить') }}
            </x-primary-button>
        </div>
    </form>

