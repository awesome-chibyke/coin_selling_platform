<?php

namespace App\Http\Controllers\Mails;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BulkMail;
use App\Models\Settings;
use App\Traits\Generics;
use App\Jobs\BulkMailSender;
use Illuminate\Http\Request;
use App\Models\MailReceivers;
use App\Models\MailAttachments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BulkMailController extends Controller
{
    use Generics;

    function __construct(User $user, BulkMail $bulkMail, MailAttachments $mailAttachments, MailReceivers $mailReceivers, Settings $settings)
    {
        $this->user = $user;
        $this->bulkMail = $bulkMail;
        $this->mailReceivers = $mailReceivers;
        $this->mailAttachments = $mailAttachments;
        $this->settings = $settings;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //select all the users
        $users = $this->user::where('type_of_user', $this->user->normalUserType)->orderBy('id', 'DESC')->get();

        return view('logged.send_mail', ['users'=>$users, 'bulkMailModelInstance'=>$this->bulkMail]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected function validator(array $data)
    {
        $validator =  Validator::make($data, [
            'title' => ['required', 'string'],
            'mail_readers' => ['required', 'string'],
            'mail_body' => ['required', 'string'],
            'filename' => ['nullable', 'array'],
            'filename.*' => ['nullable', 'file', 'image', 'mimes:jpeg,bmp,png,jpg', 'max:3000000']
        ]);

        $validator->sometimes('unsubscribe_custom_reason', 'required|string|min:5', function ($input) {
            return $input->unsubscribe_reason === 'other';
        });

        return $validator;
    }

    private function uploadFile($request){
        $filenameArray = [];
        if($request->hasFile('filename')){//upload the file
            $files = $request->file('filename');
            foreach($files as $f => $file){
                $filenameArray[] = $this->saveFiles($request, $this->mailAttachments->mailFileStoragePath, $this->mailAttachments->mailFileDefaultImageName, 'filename', 'filename', null, $f);
            }
        }
        return $filenameArray;
    }

    public function store(Request $request)
    {
        try{
            //validdate the email
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return response()->json([ 'status'=>false, 'message'=>$validate->getMessageBag(), 'data'=>[] ]);
            }

            //upload filename
            $filenameArray = $this->uploadFile($request);

            //add the data to the database
            $uniqueId = $this->createNewUniqueId('bulk_mails', 'unique_id', 20);
            $this->createBulkMail($uniqueId, $request);

            //save the users that will get the mail
            $this->saveMailReaders($request, $uniqueId);

            //save the files if its exists
            $this->saveAttachments($filenameArray, $uniqueId);

            dispatch(new BulkMailSender($uniqueId));
            //$this->initialMailSending($this->bulkMail, $this->user, $this->settings, $uniqueId);

            return response()->json([ 'status'=>true, 'message'=>'Message was successfully submitted', 'data'=>[] ]);

        }catch(\Exception $exception){
            return response()->json([ 'status'=>false, 'message'=>['general_error'=>[$exception->getMessage()] ], 'data'=>[] ]);
        }
    }

    //save the support file
    private function saveAttachments(array $filenameArray, string $bulkMailUniqueId){
        if(count($filenameArray) > 0){
            $arrayToInsert = [];
            foreach($filenameArray as $k => $eachFileName){
                $supportFileUniqueId = $this->createNewUniqueId('support_files', 'unique_id', 20);
                $mailAttachmentsArray = [];
                $mailAttachmentsArray['unique_id'] = $supportFileUniqueId;
                $mailAttachmentsArray['mail_unique_id'] = $bulkMailUniqueId;
                $mailAttachmentsArray['filename'] = $eachFileName;
                $mailAttachmentsArray['created_at'] = Carbon::now()->toDateTimeString();
                $mailAttachmentsArray['updated_at'] = Carbon::now()->toDateTimeString();
                $arrayToInsert[] = $mailAttachmentsArray;
            }
            $returnData = $this->mailAttachments::insert($arrayToInsert);
            return $returnData;
        }
        return (object)[];

    }

    private function saveMailReaders($request, $bulkMailUniqueId){
        if($request->mail_readers === $this->bulkMail->sendToSelectedUsers){
            foreach($request->selected_user_array as $k => $eachUserUniqueId){
                $arrayToInsert[] = $this->returnMailReaderArray($bulkMailUniqueId, $eachUserUniqueId);
            }
            $returnData = $this->mailReceivers::insert($arrayToInsert);
            return $returnData;
        }
    }

    private function returnMailReaderArray($mainUnqueId, $userUniqueId){
        $mailReaderArray = [];
        $mailReaderUniqueId = $this->createNewUniqueId('mail_receivers', 'unique_id', 20);
        $mailReaderArray['unique_id'] = $mailReaderUniqueId;
        $mailReaderArray['mail_unique_id'] = $mainUnqueId;
        $mailReaderArray['user_unique_id'] = $userUniqueId;
        $mailReaderArray['created_at'] = Carbon::now()->toDateTimeString();
        $mailReaderArray['updated_at'] = Carbon::now()->toDateTimeString();
        return $mailReaderArray;
    }

    private function createBulkMail($uniqueId, $request){
        $bulkMailModelInstance = $this->bulkMail;
        $bulkMailModelInstance->unique_id = $uniqueId;
        $bulkMailModelInstance->title = $request->title;
        $bulkMailModelInstance->mail_body = $request->mail_body;
        $bulkMailModelInstance->mail_readers = $request->mail_readers;
        $bulkMailModelInstance->save();
        return $bulkMailModelInstance;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}