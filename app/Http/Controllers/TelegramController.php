<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use App\Services\TelegramBot;
use Illuminate\Http\Request;
use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
class TelegramController extends Controller
{
    protected $clientService;
    /*public function __construct(
            ClientServiceInterface $clientService,
            ) {
        $this->clientService = $clientService;
        
    }*/
    /*public function inbound(Request $request)
    {
        if ($request->message) {
            $reply_to_message = $request->message['message_id'];
            $chat_id = $request->message['from']['id'];

            if (isset($request->message['text'])) {
                $message = strip_tags($request->message['text']);
                $text = "Welcome to GlowUp CRM Bot🤩🤩🤩\nPlease write your Co Admin Email 🙏🙏🙏";
                $telegramChat = TelegramChat::find($chat_id);
                if ($telegramChat) {
                    info('Telegram Bot : '.$telegramChat->user?->username.' :'.$message);
                }

                if (!$telegramChat) {
                    $inputs = [
                        'id' => $chat_id,
                    ];
                    TelegramChat::create($inputs);
                } else {
                    $text = $this->tryAccess($telegramChat, $message);
                }

                if ($text) {
                    $telegramBot = new TelegramBot();
                    if ($text == 'options') {
                        $options = 'options';
                    }
                    $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null , $options ?? null);

                    return response()->json($result, 200);
                }
            }
        }

        if ($request->callback_query) {
            $reply_to_message = $request->callback_query['message']['reply_to_message']['message_id'];
            $leadId = $request->callback_query['message']['reply_to_message']['text'];
            $chat_id = $request->callback_query['from']['id'];
            if (isset($request->callback_query['data'])) {
                $option = strip_tags($request->callback_query['data']);

                $telegramChat = TelegramChat::find($chat_id);
                if ($telegramChat) {
                    $user = User::find($telegramChat->user_id);
                    if ($user && $user->co_pipeline()->exists()) {
                        $pipeline_id = $user->co_pipeline->pluck('id');
                        $text = $this->optionsResponse($leadId, $option, $pipeline_id, $user->id);
                    }
                }

                if (isset($text)) {
                    $telegramBot = new TelegramBot();
                    $options = null;
                    if ($text == 'change_assigned_user') {
                        $options = 'users';
                    }
                    if ($text == 'change_status') {
                        $options = 'statuses';
                    }
                    $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null, $options);

                    return response()->json($result, 200);
                }
            }
        }
    }*/
    public function inbound(Request $request)
{
        
        if ($request->callback_query) {
    \Log::info('TelegramAAAAAAAAAReceived callback_query:', $request->callback_query->all());
  
}
    // ========= Handle messages =========
    if ($request->message) {
        $reply_to_message = $request->message['message_id'];
        $chat_id = $request->message['from']['id'];

        if (isset($request->message['text'])) {
            $message = strip_tags($request->message['text']);
            $text = "Welcome to GlowUp CRM Bot🤩🤩🤩\nPlease write your Co Admin Email 🙏🙏🙏";
            $telegramChat = TelegramChat::find($chat_id);

            if ($telegramChat) {
                info('Telegram Bot : '.$telegramChat->user?->username.' :'.$message);
            }

            if (!$telegramChat) {
                $inputs = [
                    'id' => $chat_id,
                ];
                TelegramChat::create($inputs);
            } else {
                $text = $this->tryAccess($telegramChat, $message);
            }

            if ($text) {
                $telegramBot = new TelegramBot();
                if ($text == 'options') {
                    $options = 'options';
                }
                $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null , $options ?? null);

                return response()->json($result, 200);
            }
        }
    }

    // ========= Handle Accept / Reject =========
    if ($request->callback_query) {
        $chat_id = $request->callback_query['from']['id'];
        $callbackData = $request->callback_query['data'];

        // مثال: accept_deposit_123
        if (preg_match('/^(accept|reject)_(deposit|withdraw)_(\d+)$/', $callbackData, $matches)) {
            $action = $matches[1]; // accept or reject
            $type   = $matches[2]; // deposit or withdraw
            $id     = $matches[3]; // ID 

            
            if ($action === 'accept') {
                // RequestModel::where('id', $id)->update(['status' => 'accepted']);
                $this->sendNotification($chat_id, "✅ $type Request #$id has been accepted.");
            } elseif ($action === 'reject') {
                // RequestModel::where('id', $id)->update(['status' => 'rejected']);
                $this->sendNotification($chat_id, "❌ $type Request #$id has been rejected.");
            }

            //Answer call back, disappear loading
            return response()->json([
                'method' => 'answerCallbackQuery',
                'callback_query_id' => $request->callback_query['id'],
                'text' => 'Action processed successfully'
            ]);
        }

        // ========= Old logic =========
        if (isset($request->callback_query['message']['reply_to_message'])) {
            $reply_to_message = $request->callback_query['message']['reply_to_message']['message_id'];
            $leadId = $request->callback_query['message']['reply_to_message']['text'];

            if (isset($callbackData)) {
                $option = strip_tags($callbackData);

                $telegramChat = TelegramChat::find($chat_id);
                if ($telegramChat) {
                    $user = User::find($telegramChat->user_id);
                    if ($user && $user->co_pipeline()->exists()) {
                        $pipeline_id = $user->co_pipeline->pluck('id');
                        $text = $this->optionsResponse($leadId, $option, $pipeline_id, $user->id);
                    }
                }

                if (isset($text)) {
                    $telegramBot = new TelegramBot();
                    $options = null;
                    if ($text == 'change_assigned_user') {
                        $options = 'users';
                    }
                    if ($text == 'change_status') {
                        $options = 'statuses';
                    }
                    $result = $telegramBot->sendMessage($text, $chat_id, $reply_to_message, null, null, $options);

                    return response()->json($result, 200);
                }
            }
        }
    }
}

    public function tryAccess($telegramChat, $message)
    {
        $text = null;

        if ($telegramChat->user_id) {
            $user = User::find($telegramChat->user_id);
            if ($user && $user->co_pipeline()->exists()) {
                if (is_numeric($message)) {
                    $text = 'options';
                }else {
                    $text = "Hello if you want to see the options please write the Lead id";
                }
            }
            else {
                $text = "😳 Sorry, You have no permission 😳";
            }
            return $text;
        }

        if ($telegramChat->times_to_try <= 0) {
            $text = "😳 Sorry, you have tried many times and can't try anymore 😳";
        } elseif ($telegramChat->verification_level == 0) {
            $user = User::where('email', $message)->whereHas('co_pipeline')->exists();
            if ($user) {
                $inputs = [
                    'verification_level' => 1,
                    'email' => $message,
                ];
                $telegramChat->update($inputs);
                $text = '✅️ Please write your password ✅️';
            } else {
                $text = "❌ Please write your correct email ❌\nYou have {$telegramChat->times_to_try} times to try";
                $inputs = [
                    'times_to_try' => $telegramChat->times_to_try -= 1,
                ];
                $telegramChat->update($inputs);
            }
        } elseif ($telegramChat->verification_level == 1) {
            $user = User::where('email', $telegramChat->email)->first();
            if ($user && Hash::check($message, $user->password)) {
                $inputs = [
                    'user_id' => $user->id,
                ];
                $telegramChat->update($inputs);
                $text = "✅️✅️✅️✅️✅️✅️✅️✅️\nWelcome {$user->first_name} {$user->last_name} to GlowUp CRM\n🤩🤩🤩🤩🤩🤩🤩🤩";
            } else {
                $text = "❌ Password is invalid ❌\nYou have {$telegramChat->times_to_try} times to try";
                $inputs = [
                    'times_to_try' => $telegramChat->times_to_try -= 1,
                ];
                $telegramChat->update($inputs);
            }
        }

        if ($message == 'the best bot') {
            $text = 'Thank you 🤩🤩🤩';
            $inputs = [
                'times_to_try' => 5,
            ];
            $telegramChat->update($inputs);
        }

        return $text;
    }

    public function optionsResponse($leadId, $option, $pipeline_id, $auth_user_id)
    {
        $text = null;
        $lead = Client::where('id', $leadId)->whereIn('pipeline_id', $pipeline_id)->first();
        //$clientController = new ClientsController;

        if ($lead) {
            switch ($option) {
                case 'get_info':
                    $text = "Name: {$lead->first_name} {$lead->last_name}\nEmail: {$lead->email}\nPhone: {$lead->phone1}" . 
                    ($lead->phone2 ? ' - ' . $lead->phone2 : '') . 
                    "\nStatus: {$lead->sales_status}\nAssigned User: {$lead->user->username}\nLast Comment: {$lead->comments->last()?->comment}";
                    break;

                case 'change_assigned_user':
                    $text = "change_assigned_user";
                    break;
                    
                case str_starts_with($option, 'change_user_id_'):
                    $userId = str_replace('change_user_id_', '', $option);
                    $user = User::findorFail($userId);
                    $authUser = User::findorFail($auth_user_id);
                    Auth::login($authUser);
                    
                    $request = new Request([
                        'client_id' => $leadId,
                        'user_id'   => $userId,
                    ]);
                    //TODO:following line sohuld be called by Dependencies in constructor, but science telegram controller called as instance in requests command we called client service in this way, it should be fixed after handeling requests in commands
$clientService = app(ClientServiceInterface::class);
                    $clientService->multiEdit($request, Auth::user());//$clientController->multiEdit($request);
                    $text = "✅ Assigned User changed to {$user->username} successfully ✅";
                    break;
                    
                case 'change_status':
                    $text = "change_status";
                    break;

                case str_starts_with($option, 'change_status_'):
                    $status = str_replace('change_status_', '', $option);
                    $authUser = User::findorFail($auth_user_id);
                    Auth::login($authUser);

                    $request = new Request([
                        'sales_status' => $status,
                        'client_id'    => $leadId,
                    ]);
//TODO:following line sohuld be called by Dependencies in constructor, but science telegram controller called as instance in requests command we called client service in this way, it should be fixed after handeling requests in commands
                    $clientService->multiEdit($request, Auth::user());//$clientController->multiEdit($request);
                    $text = "✅ Status changed to {$status} successfully ✅";
                    break;
        
                default:
                    $text = "❌ Invalid action ❌";
                    break;
            }
        }else {
            $text = "❌ Lead not found ❌";
        }

        return $text;
    }

    public function index(Request $request)
    {
        $update = $request->all();

        if (isset($update['message'])) {
            $chat_id = $update['message']['chat']['id'];
            $telegramChat = TelegramChat::find($chat_id);
            if ($telegramChat) {
                $text = $update['message']['text'] ?? '';
                if ($telegramChat->verification_level == 1) {
                    $this->sendMessage($telegramChat,$text);
                }else{
                    $this->login($telegramChat,$chat_id,$text);
                }
            }else{
                $inputs = [
                    'id' => $chat_id,
                ];
                $telegramChat = TelegramChat::create($inputs);
                $this->login($telegramChat,$chat_id);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function sendMessage($telegramChat,$message)
    {
        $token = env('TELEGRAM_CHANNEL_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHANNEL_CHAT_ID');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $user = User::find($telegramChat->user_id);

        $data = [
            'chat_id' => $chatId,
            'text'    => "{$user->channel_name} : \n{$message}",
        ];

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            info('Telegram Failed:', $response->json());
        }

        return $response->json();
    }

    private function login($telegramChat,$chatId,$text = null)
    {
        $token = env('TELEGRAM_CHANNEL_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $data = [
            'chat_id' => $chatId,
        ];

        if (!$text) {
            $data['text'] = "Welcome to FTD Bot🤩🤩🤩\nPlease write your Username 🙏🙏🙏";
        }else{
            if (!$telegramChat->email) {
                $user = User::where('username',$text)->first();
                if ($user) {
                    $telegramChat->update([
                        'email' => $text,
                    ]);
                    $data['text'] = 'Please write your Password 🙏🙏🙏';
                }else{
                    $data['text'] = 'Username is not correct ❌❌ Please Try again';
                }
            }else{
                $user = User::where('username',$telegramChat->email)->first();
                if ($user && Hash::check($text, $user->password)) {
                    $telegramChat->update([
                        'verification_level' => 1,
                        'user_id'            => $user->id,
                    ]);
                    $data['text'] = 'You are connected with the channel ✅✅✅';
                }else{
                    $data['text'] = 'Wrong Password ❌❌ Please Try again';
                    $telegramChat->update([
                        'times_to_try' => $telegramChat->times_to_try-1
                    ]);
                }
            }
        }

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            info('Telegram Failed:', $response->json());
        }

        return $response->json();
    }

    public function notification(Request $request)
    {
        $update = $request->all();

    // إذا جاء callback_query
    if (isset($update['callback_query'])) {
        $chat_id = $update['callback_query']['from']['id'];
        $callbackData = $update['callback_query']['data'];

        if (preg_match('/^(accept|reject)_(deposit|withdraw)_(\d+)$/', $callbackData, $matches)) {
            $action = $matches[1]; // accept or reject
            $type   = $matches[2]; // deposit or withdraw
            $id     = $matches[3]; // ID 

            // هنا نفذ الأكشن المناسب
            if ($action === 'accept') {
                // مثال: تحديث حالة الطلب
                // RequestModel::where('id', $id)->update(['status' => 'accepted']);
                $this->sendNotification($chat_id, "✅ $type Request #$id has been accepted.");
            } elseif ($action === 'reject') {
                // RequestModel::where('id', $id)->update(['status' => 'rejected']);
                $this->sendNotification($chat_id, "❌ $type Request #$id has been rejected.");
            }

            // الرد على التليجرام بأن الإجراء تم (اختفاء الـ loading)
            return response()->json([
                'method' => 'answerCallbackQuery',
                'callback_query_id' => $update['callback_query']['id'],
                'text' => 'Action processed successfully'
            ]);
        }
    }

        if (isset($update['message'])) {
            $chat_id = $update['message']['chat']['id'];
            //$this->sendNotification($chat_id, "✅ asd");
            
            $telegramChat = TelegramChat::where('type','notifi')->find($chat_id);
            if ($telegramChat) {
                $text = $update['message']['text'] ?? '';
                if ($telegramChat->verification_level != 1) {
                    $this->getPassword($telegramChat,$chat_id,$text);
                }else{
                    Log::channel('telegram')->info($text);
                }
            }else{
                $inputs = [
                    'id' => $chat_id,
                    'type' => 'notifi',
                ];
                $telegramChat = TelegramChat::create($inputs);
                $this->getPassword($telegramChat,$chat_id);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function getPassword($telegramChat,$chatId,$text = null)
    {
        $token = env('TELEGRAM_NOTIFICATION_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $data = [
            'chat_id' => $chatId,
        ];

        if (!$text) {
            $data['text'] = "Welcome to Notification Bot🤩🤩🤩\nPlease write The Password 🙏🙏🙏";
        }else{
            if ($telegramChat->times_to_try <= 0) {
                $text = "😳 Sorry, you have tried many times and can't try anymore 😳";
            }
            else{
                if ($text == 'Notification154575') {
                    $telegramChat->update([
                        'verification_level' => 1,
                    ]);
                    $data['text'] = 'You are connected with the Notification Bot ✅✅✅';
                }else{
                    $data['text'] = 'Wrong Password ❌❌ Please Try again';
                    $telegramChat->update([
                        'times_to_try' => $telegramChat->times_to_try-1
                    ]);
                }
            }
            if ($text == 'the best bot') {
                $data['text'] = 'Thank you 🤩🤩🤩';
                $inputs = [
                    'times_to_try' => 5,
                ];
                $telegramChat->update($inputs);
            }
        }

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            info('Telegram Failed:', $response->json());
        }

        return $response->json();
    }

    public function sendNotification($chatId,$message,$replyMarkup = null)
    {
        $token = env('TELEGRAM_NOTIFICATION_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $data = [
            'chat_id' => $chatId,
            'text'    => $message,
        ];
if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }
        
        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            info('Telegram Failed:', $response->json());
        }
    }

    public function notifi(Request $request)
    {
        $token = env('TELEGRAM_NOTIFI_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $data = [
            'chat_id' => 1205971874,
            'text'    => 'You have a notifi from trading view : '.$request->message,
        ];

        $response = Http::withHeaders($headers)->post($url, $data);
        $data = [
            'chat_id' => 5173560887,
            'text'    => 'You have a notifi from trading view : '.$request->message,
        ];
        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            info('Telegram Failed:', $response->json());
        }

        return response()->json(['status' => 'success']);
    }

    public function getId(Request $request)
    {
        $update = $request->all();

        if (isset($update['message'])) {
            $chat_id = $update['message']['chat']['id'];
            Log::channel('telegram')->info($chat_id);
        }

        return response()->json(['status' => 'success']);
    }
}
