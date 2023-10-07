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
@include('layouts.refbook-accounts')

<div class="d-flex flex-row justify-content-center">
    <table class="table table-bordered table-hover table-sm caption-top w-50 text-center align-center">
    <caption><h4 class="inline fw-weight-bolder">Категории счетов</h4></caption>
    <thead>
        <tr>
        <th scope="col">Название<h4 class="text-danger inline fw-weight-bolder">*</h4></th>
        <th scope="col">Порядковый номер<h4 class="text-danger inline fw-weight-bolder">*</h4></th>
        <th scope="col">Описание</th>
        <th scope="col"></th>
        <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($categories as $key => $category)
        <tr>
            <form class="" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('account.update')}}">
            @csrf
                <input hidden class="form-control form-control-sm" type="text" name="category" value="0"/>
                <td>
                    <input class="form-control form-control-sm" required type="text" name="name" value="{{$category->name}}"/>
                </td>
                <td>
                    <input class="form-control form-control-sm" required type="text" name="order_number" value="{{$category->order_number}}"/>
                </td>
                <td>
                    <input class="form-control form-control-sm" type="text" name="description" value="{{$category->description}}"/>
                </td>
                <td class="col-1 align-top">
                    <button type="submit" class="btn btn-primary btn-sm align-text-top" name="id" value="{{$category->id}}">Обновить</button>
                </td>
            </form>
            <form name="" id="delete" method="post" enctype="multipart/form-data" action="{{route('account.delete')}}">
            @csrf
                <td class="col-1">
                    @if (\App\Models\Account::where('category', $category->id)->where('user_id', Auth::user()->id)->count() > 0)
                        <button disabled type="submit" class="btn btn-danger btn-sm" name="id" value="{{$category->id}}">Удалить</button>
                    @else
                        <button type="submit" class="btn btn-danger btn-sm" name="id" value="{{$category->id}}" onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                    @endif
                </td>
            </form>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <form class="align-text-top" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('account.create')}}">
        @csrf
            <input hidden class="form-control form-control-sm" type="text" name="category" value="0"/>
            <td>
                <input class="form-control form-control-sm" required type="text" name="name" value=""/>
            </td>
            <td>
                <input class="form-control form-control-sm" required type="text" name="order_number" value=""/>
            </td>
            <td>
                <input class="form-control form-control-sm" type="text" name="description" value=""/>
            </td>
            <td class="col-1">
                <button type="submit" class="btn btn-primary btn-sm align-text-top" name="id" value="">Добавить</button>
            </td>
            <td>
            </td>
        </form>
    </tfoot>
    </table>
</div>

<div class="d-flex flex-row justify-content-center">
    <table class="table table-bordered table-hover table-sm caption-top w-50 text-center align-center">
    <caption><h4 class="inline fw-weight-bolder">Счета</h4></caption>
    <thead>
        <tr>
        <th scope="col">Название<h4 class="text-danger inline fw-weight-bolder">*</h4></th>
        <th scope="col">Категория<h4 class="text-danger inline fw-weight-bolder">*</h4></th>
        <th scope="col">Порядковый номер<h4 class="text-danger inline fw-weight-bolder">*</h4></th>
        <th scope="col">Описание</th>
        <th scope="col"></th>
        <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($subCategories as $key => $subCategory)
        <tr>
            <form class="" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('account.update')}}">
            @csrf
                <td>
                    <input class="form-control form-control-sm" required type="text" name="name" value="{{$subCategory->name}}"/>
                </td>
                <td>
                    <select name="category" class="form-select form-select-sm">
                        @foreach ($categories as $key => $category)
                            @if ($subCategory->category == $category->id)
                                <option selected value="{{$category->id}}">{{$category->name}}</option>
                            @else
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control form-control-sm" required type="text" name="order_number" value="{{$subCategory->order_number}}"/>
                </td>
                <td>
                    <input class="form-control form-control-sm" type="text" name="description" value="{{$subCategory->description}}"/>
                </td>
                <td class="col-1 align-top">
                    <button type="submit" class="btn btn-primary btn-sm align-text-top" name="id" value="{{$subCategory->id}}">Обновить</button>
                </td>
            </form>
            <form name="" id="delete" method="post" enctype="multipart/form-data" action="{{route('account.delete')}}">
            @csrf
                <td class="col-1">
                    <button type="submit" class="btn btn-danger btn-sm" name="id" value="{{$subCategory->id}}" onclick="return confirm('Уверены, что хотите удалить?');">Удалить</button>
                </td>
            </form>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <form class="align-text-top" name="" id="category" method="post" enctype="multipart/form-data" action="{{route('account.create')}}">
        @csrf
            <td>
                <input class="form-control form-control-sm" required type="text" name="name" value=""/>
            </td>
            <td>
                    <select name="category" class="form-select form-select-sm">
                        @foreach ($categories as $key => $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </td>
            <td>
                <input class="form-control form-control-sm" required type="text" name="order_number" value=""/>
            </td>
            <td>
                <input class="form-control form-control-sm" type="text" name="description" value=""/>
            </td>
            <td class="col-1">
                <button type="submit" class="btn btn-primary btn-sm align-text-top" name="id" value="">Добавить</button>
            </td>
            <td>
            </td>
        </form>
    </tfoot>
    </table>
</div>
