<head>
    <title>Бюджет. Версия 2.0.1</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap.bundle.min.js"></script>
</head>

<div class="container text-center">
    <div class="row align-items-around">
        <div class="col">
            <b>Семейный бюджет. Автор Парфентий Петр</b>
        </div>
        <div class="col">
            <b>Сегодня:
                @php
                    $tz = 'Europe/Moscow';
                    $timestamp = time();
                    $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
                    $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
                    echo $dt->format('d.m.Y, H:i:s');
                @endphp
            </b>
        </div>
        <div class="col">
            <b>Версия 2.0.1</b>
        </div>
    </div>
</div>
<hr>
