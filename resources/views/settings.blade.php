@php
    $setting['is_tbot_active'] = is_null($settings) ? false : $settings->is_tbot_active;
    $setting['tbot_token'] = is_null($settings) ? "" : $settings->tbot_token;
    $setting['tbot_channel_id'] = is_null($settings) ? "" : $settings->tbot_channel_id;;
@endphp

<x-header/>
<x-app-layout/>
<div>
    <div class="d-flex flex-row justify-content-center">
        <h2>Настройки</h2>
    </div>
    <form class="formform-control" id="category" method="post" enctype="multipart/form-data" action="{{route('settings.save')}}">
        @csrf
        <div class="d-flex flex-row justify-content-center">
            <div class="d-flex flex-column justify-content-center fw-bold border border-secondary px-3">
                Настройки telegramm-бота:
            </div>
            <div class="d-flex flex-column justify-content-start border border-secondary px-3">
                <div class="d-flex flex-column justify-content-center">
                    Активирован:
                </div>
                <div class="d-flex flex-column justify-content-start text-center align-content-center form-check form-switch">
                    @if ($setting['is_tbot_active'])
                        <input id="isTBotActive" class="form-check-input" type="checkbox" name="isTBotActive" checked>
                    @else
                        <input id="isTBotActive" class="form-check-input" type="checkbox" name="isTBotActive">
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column justify-content-start border border-secondary px-3">
                Ваш Telegram ID:
                <x-text-input id="tBotChannel" class="form-control form-control-sm w-auto block mt-1 items-center justify-center" type="text" name="tBotChannel" value="{{$setting['tbot_channel_id']}}" required autofocus autocomplete="tBotChannel" />
                <x-input-error :messages="$errors->get('tBotChannel')" class="mt-2" />
            </div>
            <div class="d-flex flex-column justify-content-start px-3">
                <p>Получить Ваш Telegram ID - <a href="https://t.me/getmyid_bot">https://t.me/getmyid_bot</a></p>
                <p>Ссылка на бот Бюджета <a href="https://t.me/XammepBudgetBot">https://t.me/XammepBudgetBot</a></p>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center">

        </div>
        <div class="d-flex flex-row justify-content-center my-2">
            <button type="submit" class="btn btn-primary btn-sm shadow-lg" name="" value="">Сохранить</button>
        </div>
    </form>
</div>

