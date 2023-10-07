<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    protected $mainMenu = [ 'inline_keyboard' => [[
                        [
                            'text' => 'Новая проводка',
                            'callback_data' => 'newTransaction',
                        ],
                        [
                            'text' => 'Последние проводки',
                            'callback_data' => 'lastTransactions',
                        ],
                        [
                            'text' => 'Бюджеты в PDF',
                            'callback_data' => 'budgetsPdf',
                        ],
                    ]]];

    public function send($message) {
        $userSetting = Setting::where('user_id', Auth::user()->id)->first();
        if ($userSetting->is_tbot_active) {
            $idChannel = $userSetting->tbot_channel_id;

            $response = Telegram::sendMessage([
                'chat_id' => $idChannel,
                'text' => $message,
            ]);

            $messageId = $response->getMessageId();
            Log::info($messageId);
        }
    }

    public function getFromBot() {
        $updates = Telegram::getWebhookUpdate();
        Log::info('==============');
        Log::info($updates);

        $preparedMessage = $this->prepareMessage($updates);
        $user = Setting::where('tbot_channel_id', $preparedMessage['chatId'])->firstOr(function () {
            Log::info('Пользователь не существует');

            return 'Restricted';
        });
        //Log::info('UserData - ' . $user);
        if ($user === 'Restricted') {
            $response = Telegram::sendMessage([
                'chat_id' => $preparedMessage['chatId'],
                'text' => 'Отказано в доступе',
            ]);
        }
        else {
            if ($user->is_tbot_active) {
                Log::info('Пользователь ' . $preparedMessage['chatId'] . ' пишет:');
                $this->mainLogic($preparedMessage);
            }
        }

        return 'ok';
    }

    private function prepareMessage($sourceMessage)
    {
        if (isset($sourceMessage['message'])) {
            $preparedMessage = [
                'type' => 'textFromUser',
                'chatId' => $sourceMessage['message']['from']['id'],
                'text' => $sourceMessage['message']['text'],
            ];
        } elseif (isset($sourceMessage['channel_post'])) {
            $preparedMessage = [
                'type' => 'textFromChannel',
                'chatId' => $sourceMessage['channel_post']['sender_chat']['id'],
                'text' => $sourceMessage['channel_post']['text'],
            ];
        } elseif (isset($sourceMessage['callback_query'])) {
            $preparedMessage = [
                'type' => 'button',
                'chatId' => $sourceMessage['callback_query']['message']['chat']['id'],
                'text' => $sourceMessage['callback_query']['data'],
            ];
        } else {
            Log::info('Wrong message');
        }

        return $preparedMessage;
    }

    private function mainLogic($messageArray) {
        $user = Setting::where('tbot_channel_id', $messageArray['chatId'])->first();
        Log::info('Current menu position: ' . $user->menu_position);
        $menuPosition = $user->menu_position;

        if ($messageArray['type'] === 'textFromUser' || $messageArray['type'] === 'textFromChannel') {
            switch ($messageArray['text']) {
                case '/help':
                    $message = "Нужна помощь?";
                    $reply_markup = json_encode(['inline_keyboard' => []]);
                    break;
                default:
                    $message = "Основное меню";
                    $reply_markup = json_encode($this->mainMenu);

            }
        } elseif ($messageArray['type'] === 'button') {
            switch ($messageArray['text']) {
                case 'lastTransactions':
                    $message = "Задайте количество последних проводок";
                    $reply_markup = json_encode(['inline_keyboard' => []]);
                    $menuPosition = '2.1';
                    break;
                case 'button2':
                    $message = "Нажата кнопка2";
                    $reply_markup = json_encode(['inline_keyboard' => []]);
                    break;
                default:
                    $message = "Хер пойми что нажато";
                    $reply_markup = json_encode(['inline_keyboard' => []]);
                    break;

            }
        }
        $response = Telegram::sendMessage([
            'chat_id' => $messageArray['chatId'],
            'text' => $message,
            'reply_markup' => $reply_markup,
        ]);

        $user->menu_position = $menuPosition;
        $user->save();
    }

    public function setWebHook() {
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        Log::info($response);
        return;
    }

    public function getMe() {
        $response = Telegram::bot('mybot')->getMe();
        Log::info($response);
        return;
    }

}
