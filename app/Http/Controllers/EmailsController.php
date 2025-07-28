<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmailTemplateRequest;
use App\Http\Requests\SendEmailRequest;
use App\Mail\MarketingMail;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Models\MarketingEmailLog;
use App\Models\SenderEmail;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

//Services
use App\Http\Services\Client\Interfaces\ClientServiceInterface;
use App\Http\Services\User\Interfaces\UserServiceInterface;

class EmailsController extends Controller
{
    protected $clientService;
    protected $userService;
    public function __construct(
            ClientServiceInterface $clientService,
            UserServiceInterface $userService,
            ) {
        $this->clientService = $clientService;
        $this->userService = $userService;
        
    }
    
    public function index(Request $request)
    {
        $marketingEmailLogs = MarketingEmailLog::where('user_id','!=',null);
        //$clientsController  = new ClientsController;
        //$userController     = new UserController;
        $emailTemplates     = EmailTemplate::latest()->get();
        $senderEmails       = SenderEmail::select('id','email')->latest()->get();
        $statuses           = Status::select('id', 'name')->latest()->get();
        $options            = $this->userService->getUserOptions(Auth::user());//$userController->get_user_options();
        $teams              = $this->clientService->getTeams($options, Auth::user());//$clientsController->getTeams($options);
        $limit              = $request->input('limit', 6);

        $leads = Client::select('id', 'first_name', 'last_name', 'email', 'phone1', 'phone2', 'country', 'sales_status')
            ->whereNotNull('email')
            ->where('email', '!=', "")
            ->where('email', '!=', " ")
            ->whereRaw('TRIM(email) != ?', [''])
            ->whereRaw('email REGEXP ?', ['^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$'])
            ->whereRaw("SUBSTRING_INDEX(email, '@', 1) NOT REGEXP '^[0-9]+$'")
            ->where('deleted', 0)
            ->distinct('email')
            ->latest()
            ->get();

        if ($filters = $request->get('filters', [])) {
            $marketingEmailLogs->where(function ($query) use ($filters) {
                $query->where(function ($subquery) use ($filters) {
                    if ($textQuery = Arr::get($filters, 'search_marketingEmailLogs')) {
                        $textQuery = strtolower($textQuery);
                        $textQuery = '%'.$textQuery.'%';
                        $subquery->where(DB::raw('LOWER(text)'), 'like', $textQuery)
                            ->orWhere(DB::raw('REPLACE(text,"\n", " ")'), 'like', $textQuery)
                            ->orWhere(DB::raw('REPLACE(text,"\r\n", " ")'), 'like', $textQuery)
                            ->orWhereHas('user', function ($subsubquery) use ($textQuery) {
                                $subsubquery->where(DB::raw('LOWER(username)'), 'like', $textQuery)
                                    ->orWhere(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                    ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                            })
                            ->orWhereHas('client', function ($subsubquery) use ($textQuery) {
                                $subsubquery->where(DB::raw('LOWER(first_name)'), 'like', $textQuery)
                                    ->orWhere(DB::raw('LOWER(last_name)'), 'like', $textQuery);
                            })
                            ->orWhere(DB::raw('LOWER(client_id)'), 'like', $textQuery);
                    }
                });
                if ($fromDate = Arr::get($filters, 'fromTo_marketingEmailLogs')) {
                    $dates = preg_split('/\s*-\s*/', trim($fromDate));

                    if (isset($dates[0]) && !empty($dates[0])) {
                        $formattedFromDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '>=', $formattedFromDate);
                    }
                    if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    }else{
                        $formattedToDate = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
                        $query->where('created_at', '<=', $formattedToDate);
                    }
                }
            });
        }

        $marketingEmailLogs = $marketingEmailLogs->latest()->paginate($limit);

        return view('emails.index', compact(
            'marketingEmailLogs',
            'emailTemplates',
            'senderEmails',
            'statuses',
            'filters',
            'leads',
            'teams'
        ));
    }

    public function send(SendEmailRequest $request)
    {
        $client_emails = $request->client_emails;
        $client_emails = explode(',', $client_emails);
        $emailsString  = $request->client_emails2;
        $senderEmail   = SenderEmail::findOrFail($request->sender_email_id);
        $emailArray    = explode(',', $emailsString);
        $client_emails = array_filter(array_merge($client_emails??[], $emailArray));
        $template_id   = $request->template_id;

        $data = [
            'company_name' => $senderEmail->company_name,
            'encryption'   => $senderEmail->encryption,
            'username'     => $senderEmail->username,
            'password'     => $senderEmail->password,
            'email'        => $senderEmail->email,
            'host'         => $senderEmail->host,
            'port'         => $senderEmail->port,
        ];

        if ($request->template_id) {
            $emailTemplate = EmailTemplate::find($request->template_id);
            $data = array_merge($data, ['subject' => $emailTemplate->subject, 'body' => $emailTemplate->body]);
            if ($emailTemplate->attachment) {
                $attachments = json_decode($emailTemplate->attachment, true) ?? [];
            
                $inaccessibleUrls = [];
            
                foreach ($attachments as $attachmentUrl) {
                    $headers = @get_headers($attachmentUrl);
            
                    if (!$headers || strpos($headers[0], '200 OK') === false) {
                        $inaccessibleUrls[] = $attachmentUrl;
                    }
                }
            
                if (!empty($inaccessibleUrls)) {
                    $inaccessibleList = implode(', ', $inaccessibleUrls);
                    return redirect()->back()->with('fail', "The following attachment URLs are not accessible: $inaccessibleList");
                }
            
                $data = array_merge($data, ['attachment' => $attachments]);
            }
            
        }else{
            $data = array_merge($data, ['subject' => $request->subject, 'body' => $request->body]);
            if ($request->save_as_template) {
                $inputs = $request->only('subject', 'body', 'name');
            }
            if ($request->hasFile('attachment')) {
                $filePaths = [];
            
                foreach ($request->file('attachment') as $file) {
                    $filePath = $file->store('attachments', 'public');
                    $filePaths[] = env('APP_URL') . '/storage'.'/' . $filePath;
                }
                
                $data = array_merge($data, ['attachment' => $filePaths]);
                if ($request->save_as_template) {
                    $inputs = array_merge($inputs, ['attachment' => json_encode($filePaths)]);
                }
            }
            if ($request->save_as_template) {
                $template = EmailTemplate::create($inputs);
                $template_id = $template->id;
            }
        }


        foreach ($client_emails as $client_email) {
            Mail::to($client_email)->send(new MarketingMail($data));
        }

        $inputs = [
            'user_id'         => Auth::id(),
            'client_id'       => $request->client_id,
            'template_id'     => $template_id,
            'sender_email_id' => $request->sender_email_id,
            'text'            => 'Sent to: '.count($client_emails).' emails',
        ];

        MarketingEmailLog::create($inputs);

        return redirect()->back()->with('success', 'Email sent successfully');
    }

    public function create()
    {
        $emailTemplate = new EmailTemplate;
        
        return view('emails.show', compact('emailTemplate'));
    }

    public function store(CreateEmailTemplateRequest $request)
    {
        $inputs = $request->only('name', 'subject', 'body');
        if ($request->hasFile('attachment')) {
            $filePaths = [];
        
            foreach ($request->file('attachment') as $file) {
                $filePath = $file->store('attachments', 'public');
                $filePaths[] = env('APP_URL') . '/storage'.'/' . $filePath;
            }
        
            $inputs = array_merge($inputs, ['attachment' => json_encode($filePaths)]);
        }

        EmailTemplate::create($inputs);
        
        return redirect()->route('emails.index')->with('success', 'Email template created successfully');
    }

    public function show($id)
    {
        $emailTemplate = EmailTemplate::findOrFail($id);

        $emailTemplate->attachment = json_decode($emailTemplate->attachment, true);
        
        return view('emails.show', compact('emailTemplate'));
    }

    public function update(CreateEmailTemplateRequest $request,$id)
    {
        $emailTemplate = EmailTemplate::findOrFail($id);

        $inputs = $request->only('name', 'subject', 'body');

        if ($request->hasFile('attachment')) {
            $newFilePaths = [];
        
            if (!empty($emailTemplate->attachment)) {
                $filePaths = json_decode($emailTemplate->attachment, true) ?? [];
                
                foreach ($filePaths as $oldFilePath) {
                    $oldFileRelativePath = str_replace(env('APP_URL') . '/storage'.'/', '', $oldFilePath);
                    Storage::disk('public')->delete($oldFileRelativePath);
                }
            }
        
            foreach ($request->file('attachment') as $file) {
                $newFilePath = $file->store('attachments', 'public');
                $newFilePaths[] = env('APP_URL') . '/storage'.'/' . $newFilePath;
            }
        
            $inputs = array_merge($inputs, ['attachment' => json_encode($newFilePaths)]);
        }        

        $emailTemplate->update($inputs);
        
        return redirect()->route('emails.index')->with('success', 'Email template updated successfully');
    }

    public function delete($id)
    {
        $emailTemplate = EmailTemplate::findOrFail($id);

        $emailTemplate->delete();
        
        return redirect()->route('emails.index')->with('success', 'Email template deleted successfully');
    }
}
