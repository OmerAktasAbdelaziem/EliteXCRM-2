<?php

namespace App\Console\Commands;

use App\Http\Controllers\RequestController;
use App\Http\Controllers\TelegramController;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Models\TelegramChat;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;

class Requests extends Command
{
    protected $signature   = 'app:Requests';
    protected $description = 'check requests and send notifications';

    protected $clientService;
    protected $telegramController;

    public function __construct(ClientServiceInterface $clientService)
    {
        parent::__construct();
        $this->clientService = $clientService;
    }

    public function handle()
    {
        $request_data = (new RequestController)->get_all_request_data();
        foreach ($request_data as $request) {
            $exist = Notification::firstWhere('partner_id', $request->id);
            if (!$exist) {
                $message = "🤩🤩🤩 You have a new " . $request->type . " Request \n" .
                          "Client: " . ($request->client->first_name ?? 'N/A') . "\n" .
                          "Amount: " . ($request->amount ?? 'N/A') . "\n" .
                          "Comment: " . ($request->comment ?? 'N/A') . "\n";

                if ($request->type == 'deposit') {
                    $message .= "Details: " . ($request->usdt != null ? $request->usdt : ($request->bank?->country . " : " . $request->bank?->name));
                } else {
                    if ($request->usdt != null) {
                        $message .= "Details: " . $request->usdt;
                    } else {
                        $message .= "Details: \n" .
                            "Iban: " . $request->bank_details['iban'] . "\n" .
                            "Swift: " . $request->bank_details['swift'] . "\n" .
                            "Currency: " . $request->bank_details['currency'] . "\n" .
                            "Bank Name: " . $request->bank_details['bank_name'] . "\n" .
                            "Bank Country: " . $request->bank_details['bank_country'] . "\n" .
                            "Bank Address: " . $request->bank_details['bank_address'] . "\n" .
                            "Beneficiary Name: " . $request->bank_details['beneficiary_name'] . "\n" .
                            "Beneficiary Address: " . $request->bank_details['beneficiary_address'] . "\n" .
                            "ABA Routing Number: " . $request->bank_details['aba_routing_number'] . "\n" .
                            "Beneficiary Country: " . $request->bank_details['beneficiary_country'];
                    }
                }

                $chats = TelegramChat::where('verification_level',1)->where('type','notifi')->get();
                foreach ($chats as $chat) {
                    // Validate message is not empty before sending
                    if (!empty(trim($message))) {
                        $telegramController = new TelegramController($this->clientService);
                        $telegramController->sendNotification($chat->id,$message);
                    }
                }
                Notification::create([
                    'type'       => '0',
                    'partner_id' => $request->id,
                ]);
            }
        }
    }
}