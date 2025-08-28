<?php

namespace App\Services;

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\UserController;
use App\Models\Status;
use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;

class TelegramBot
{
    protected $token;

    protected $api_endpoint;

    protected $headers;

   

    public function __construct(
         
            )
    {
        $this->token = env('TELEGRAM_BOT_TOKEN');
        $this->api_endpoint = 'https://api.telegram.org';
        $this->setHeaders();
        

    }

    protected function setHeaders()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function sendMessage($text, $chat_id, $reply_to_message_id = null, $buttonText = null, $buttonLink = null, $optionsType = null)
    {
        $user_id = TelegramChat::find($chat_id)->user_id;
        $result = ['success' => false, 'body' => []];

        $params = [
            'reply_to_message_id' => $reply_to_message_id,
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        if ($buttonLink !== null) {
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => $buttonText, 'url' => $buttonLink],
                    ],
                ],
            ];

            $params = array_merge($params, [
                'reply_markup' => json_encode($keyboard),
            ]);
        }

        if ($optionsType) {
            unset($params['text']);
            $params = array_merge($params, $this->get_options($optionsType, $user_id));
        }

        $url = "{$this->api_endpoint}/bot{$this->token}/sendMessage";

        try {
            $response = Http::withHeaders($this->headers)->post($url, $params);
//dd($url);
            if ($response->successful()) {
                $result = ['success' => true, 'body' => $response->json()];
            } else {
                $result['error'] = "HTTP Error: {$response->status()} - {$response->body()}";
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function get_options($optionsType, $user_id){
        $clientService = app(ClientServiceInterface::class);
    //$userService = app(UserServiceInterface::class);
        if ($optionsType == 'users') {
            $user = User::find($user_id);
            Auth::login($user);
            

            //$clientsController = new ClientsController;
            //$userController    = new UserController;
            $inlineKeyboard    = [];
            //$options           = $userService->getUserOptions(Auth::user());//$userController->get_user_options();
            $teams             = $clientService->getTeams(Auth::user());//$clientsController->getTeams($options);
            $users             = $clientService->getUsers($teams, Auth::user());//$clientsController->getUsers($teams);
            $row               = [];

            foreach ($users as $index => $user) {
                $row[] = ['text' => $user->username, 'callback_data' => 'change_user_id_' . $user->id];
                
                if (($index % 2 == 1) || ($index == count($users) - 1)) {
                    $inlineKeyboard[] = $row;
                    $row = [];
                }
            }
            
            $text = "✅ Please select User :";
        }
        elseif ($optionsType == 'statuses') {
            $user = User::find($user_id);
            Auth::login($user);
            

            //$clientsController = new ClientsController;
            //$userController    = new UserController;
            $inlineKeyboard    = [];
            //$options           = $userService->getUserOptions(Auth::user());//$userController->get_user_options();
            $teams             = $clientService->getTeams(Auth::user());//$clientsController->getTeams($options);
            $parts             = $clientService->getParts($teams, Auth::user());//$clientsController->getParts($teams);
            $row               = [];

            $statuses = Status::where(function ($query) use ($parts) {
                $first = true;
                foreach ($parts as $part) {
                    if ($first) {
                        $query->where('part_ids', 'LIKE', '%"'.$part->id.'"%');
                        $first = false;
                    } else {
                        $query->orWhere('part_ids', 'LIKE', '%"'.$part->id.'"%');
                    }
                }
            })->latest()->get();            
            

            foreach ($statuses as $index => $status) {
                $row[] = ['text' => $status->name, 'callback_data' => 'change_status_' . $status->name];
                
                if (($index % 2 == 1) || ($index == count($statuses) - 1)) {
                    $inlineKeyboard[] = $row;
                    $row = [];
                }
            }

            $text = "✅ Please select status :";
        }else {
            $inlineKeyboard = [
                [
                    ['text' => 'Get Info', 'callback_data' => 'get_info'],
                    ['text' => 'Change Status', 'callback_data' => 'change_status'],
                ],
                [
                    ['text' => 'Change Assigned User', 'callback_data' => 'change_assigned_user'],
                ]
            ];
            $text = "✅ Please select an option below :";
        }

        $replyMarkup = [
            'inline_keyboard' => $inlineKeyboard
        ];

        $response = [
            'text' => $text,
            'reply_markup' => json_encode($replyMarkup)
        ];

        return $response;
    }
}
