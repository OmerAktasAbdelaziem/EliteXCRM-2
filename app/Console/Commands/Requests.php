<?php

namespace App\Console\Commands;

use App\Http\Controllers\RequestController;
use App\Http\Controllers\TelegramController;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Models\TelegramChat;
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use Illuminate\Support\Facades\Log;

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
                    $details = $request->usdt ?? ($request->bank?->country . " : " . $request->bank?->name);
                    $message .= "Details: " . ($details ?: 'N/A');
                } else {
                    if ($request->usdt != null) {
                        $message .= "Details: " . $request->usdt;
                    } else {
                        $bankDetails = [];
                        if (!empty($request->bank_details['iban'])) {
                            $bankDetails[] = "Iban: " . $request->bank_details['iban'];
                        }
                        if (!empty($request->bank_details['swift'])) {
                            $bankDetails[] = "Swift: " . $request->bank_details['swift'];
                        }
                        if (!empty($request->bank_details['currency'])) {
                            $bankDetails[] = "Currency: " . $request->bank_details['currency'];
                        }
                        if (!empty($request->bank_details['bank_name'])) {
                            $bankDetails[] = "Bank Name: " . $request->bank_details['bank_name'];
                        }
                        if (!empty($request->bank_details['bank_country'])) {
                            $bankDetails[] = "Bank Country: " . $request->bank_details['bank_country'];
                        }
                        if (!empty($request->bank_details['bank_address'])) {
                            $bankDetails[] = "Bank Address: " . $request->bank_details['bank_address'];
                        }
                        if (!empty($request->bank_details['beneficiary_name'])) {
                            $bankDetails[] = "Beneficiary Name: " . $request->bank_details['beneficiary_name'];
                        }
                        if (!empty($request->bank_details['beneficiary_address'])) {
                            $bankDetails[] = "Beneficiary Address: " . $request->bank_details['beneficiary_address'];
                        }
                        if (!empty($request->bank_details['aba_routing_number'])) {
                            $bankDetails[] = "ABA Routing Number: " . $request->bank_details['aba_routing_number'];
                        }
                        if (!empty($request->bank_details['beneficiary_country'])) {
                            $bankDetails[] = "Beneficiary Country: " . $request->bank_details['beneficiary_country'];
                        }
                        
                        if (!empty($bankDetails)) {
                            $message .= "Details: \n" . implode("\n", $bankDetails);
                        } else {
                            $message .= "Details: N/A";
                        }
                    }
                }

                $chats = TelegramChat::where('verification_level',1)->where('type','notifi')->get();
                foreach ($chats as $chat) {
                    // Log the message content for debugging
                    Log::info('Telegram message to send:', [
                        'chat_id' => $chat->id,
                        'message_length' => strlen($message),
                        'message_preview' => substr($message, 0, 100),
                        'request_id' => $request->id,
                        'request_type' => $request->type
                    ]);
                    
                    // Validate message is not empty before sending
                    if (!empty(trim($message))) {
                        $telegramController = new TelegramController($this->clientService);
                        $telegramController->sendNotification($chat->id,$message);
                    } else {
                        Log::warning('Skipping empty Telegram message', [
                            'chat_id' => $chat->id,
                            'request_id' => $request->id,
                            'request_type' => $request->type
                        ]);
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