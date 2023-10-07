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

<div class="container">
    <div class="text-center bd-highlight">
        <h2>Отчеты</h2>
    </div>
    <div class="p-2 d-grid">
        <div class="mb-3 d-grid col-3 mx-auto">
            <a href="{{url('/reports/transactions')}}" class="btn btn-primary shadow-lg">Журнал проводок</a>
        </div>
        <div class="mb-3 d-grid col-3 mx-auto">
            <a href="{{route('reports.budgets')}}" class="btn btn-primary shadow-lg" class="btn btn-primary shadow-lg">Бюджеты</a>
        </div>
        <div class="mb-3 d-grid col-3 mx-auto">
            <a href="#" class="btn btn-primary shadow-lg"></a>
        </div>
    </div>
</div>
