<?php

namespace App\Console\Commands;

use App\Http\Controllers\RequestController;
use App\Http\Controllers\TelegramController;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Models\TelegramChat;

class Requests extends Command
{
    protected $signature   = 'app:Requests';
    protected $description = 'check requests and send notifications';

    public function handle()
    {
        $request_data = (new RequestController)->get_all_request_data();
        foreach ($request_data as $request) {
            $exist = Notification::firstWhere('partner_id', $request->id);
            if (!$exist) {
                $message = "🤩🤩🤩 You have a new " . $request->type . " Request \n" ."Client: " . $request->client->first_name . "\n" ."Amount: " . $request->amount . "\n" ."Comment: " . $request->comment . "\n";

                if ($request->type == 'deposit') {
                    $message .= "Details: " . ($request->usdt != null ? $request->usdt : ($request->bank?->country . " : " . $request->bank?->name));
                } else {
                    if ($request->usdt != null) {
                        $message .= "Details: " . $request->usdt;
                    } else {
                        $message .= "Details: \n" .
                            "Iban: " . ($request->bank_details['iban']??'') . "\n" .
                            "Swift: " . ($request->bank_details['swift']??'') . "\n" .
                           // "Currency: " . ($request->bank_details['currency'] . "\n" .
                            "Bank Name: " . ($request->bank_details['bank_name']??'') . "\n" .
                            "Bank Country: " . ($request->bank_details['bank_country']??'') . "\n" .
                            "Bank Address: " . ($request->bank_details['bank_address']??'') . "\n" .
                            "Beneficiary Name: " . ($request->bank_details['beneficiary_name']??'') ;
                            //"Beneficiary Address: " . $request->bank_details['beneficiary_address'] . "\n" .
                            //"ABA Routing Number: " . $request->bank_details['aba_routing_number'] . "\n" .
                           // "Beneficiary Country: " . $request->bank_details['beneficiary_country'];
                    }
                }

                $chats = TelegramChat::where('verification_level',1)->where('type','notifi')->get();
                foreach ($chats as $chat) {
                    $replyMarkup = null;
                    if (in_array($request->type, ['deposit', 'withdraw'])) {
        $replyMarkup = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ Accept', 'callback_data' => 'accept_' . $request->type . '_' . $request->id],
                    ['text' => '❌ Reject', 'callback_data' => 'reject_' . $request->type . '_' . $request->id]
                ]
            ]
        ];
    }
                    (new TelegramController)->sendNotification($chat->id,$message,$replyMarkup);
                }
                Notification::create([
                    'type'       => '0',
                    'partner_id' => $request->id,
                ]);
            }
        }
    }
}