<?php

namespace App\Http\Controllers\Support;

use App\Models\User;
use App\Traits\Generics;
use App\Models\SupportFiles;
use Illuminate\Http\Request;
use App\Models\Support\Support;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Support\SupportMessage;
use App\Models\Support\SupportTicketCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    use Generics;

    function __construct(Support $support, SupportMessage $supportMessage, User $user, SupportFiles $supportFiles, SupportTicketCategory $supportTicketCategory)
    {
        $this->support = $support;
        $this->supportMessage = $supportMessage;
        $this->user = $user;
        $this->supportFiles = $supportFiles;
        $this->supportTicketCategory = $supportTicketCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userUniqueId)
    {
        $userobject = $this->user::where('unique_id', $userUniqueId)->first();
        $supportQuery = $this->support;
        $supportQuery = $userobject->type_of_user !== $this->user->normalUserType ? $supportQuery : $supportQuery->where('supports.user_id', '=', $userUniqueId);
        $supportQuery = $supportQuery->orderBy('id', 'DESC');
        $supportArray = $supportQuery->paginate(2);

        $pagination = count($supportArray) > 0 ? $this->myPagination($supportArray, "/support-summary/$userUniqueId?page=", 3, true) : '';

        return view('logged.support_summary', ['support_array'=>$supportArray, 'pagination_details'=>$pagination]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($supportUniqueId = null)
    {
        $userObject = Auth()->user();
        $supportObject = null;
        if($supportUniqueId !== null){
            $supportObject = $this->support::where('unique_id', $supportUniqueId)
            ->first();

            $this->updateSupportToRead($supportObject, $this->support, $userObject);
            $this->updateSupportMessageToRead($supportObject, $this->support, $userObject);
        };

        $ticketCategory = $this->supportTicketCategory::orderBy('id', 'DESC')->get();

        return view('logged.create_support', ['support_object'=>$supportObject, 'support_ticket_category'=>$ticketCategory, 'image_folder'=>$this->supportFiles->supportFileStoragePath]);
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
            'topic' => ['required', 'string', 'min:5'],
            'message'=>['required', 'string', 'min:5'],
            'category_unique_id'=>['required', 'string', 'min:5'],
            'filename' => ['nullable', 'array'],
            'filename.*' => ['nullable', 'file', 'image', 'mimes:jpeg,bmp,png,jpg', 'max:3000000']
        ]);

        return $validator;
    }

    public function storeInitialMessage(Request $request)
    {
        try{
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            $userObject = Auth()->user();
            $filenameArray = $this->uploadFile($request);

            //save the data to db
            $uniqueId = $this->createNewUniqueId('supports', 'unique_id', 20);
            $mainSupport = $this->createMainSupport($request, $this->support, $uniqueId, $userObject);

            $messageUniqueId = $this->createNewUniqueId('support_messages', 'unique_id', 20);
            $this->createMessageSupport($messageUniqueId, $uniqueId, $request, $this->supportMessage);

            $this->saveSupportFile($filenameArray, $messageUniqueId);
            return redirect()->route('create-support', [$mainSupport->unique_id])->with('success', 'Message was successfully submitted');

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }

    //save the support file
    private function saveSupportFile(array $filenameArray, string $messageUniqueId){
        if(count($filenameArray) > 0){
            $arrayToInsert = [];
            foreach($filenameArray as $k => $eachFileName){
                $supportFileUniqueId = $this->createNewUniqueId('support_files', 'unique_id', 20);
                $supportFile = [];
                $supportFile['unique_id'] = $supportFileUniqueId;
                $supportFile['support_message_unique_id'] = $messageUniqueId;
                $supportFile['filename'] = $eachFileName;
                $arrayToInsert[] = $supportFile;
            }
            $returnData = $this->supportFiles::insert($arrayToInsert);
            return $returnData;
        }
        return (object)[];

    }

    private function uploadFile($request){
        $filenameArray = [];
        if($request->hasFile('filename')){//upload the file
            $files = $request->file('filename');
            foreach($files as $f => $file){
                $filenameArray[] = $this->saveFiles($request, $this->supportFiles->supportFileStoragePath, $this->supportFiles->supportFileDefaultImageName, 'filename', 'filename', null, $f);
            }
        }
        return $filenameArray;
    }

     protected function validateContiniousReplyData(array $data)
    {
        $validator =  Validator::make($data, [
            'message'=>['required', 'string', 'min:5'],
            'filename' => ['nullable', 'array'],
            'filename.*' => ['nullable', 'file', 'image', 'mimes:jpeg,bmp,png,jpg', 'max:3000000']
        ]);

        return $validator;
    }

    //store continous message
    public function storeContiniousMessage(Request $request)
    {
        try{
            $validate = $this->validateContiniousReplyData($request->all());
            if($validate->fails()){
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            $userObject = Auth()->user();
            $filenameArray = $this->uploadFile($request);

            //select the main message from main table
            $mainMessageObject = $this->support::where('unique_id', $request->support_unique_id)->first();
            if($mainMessageObject === null){ throw new \Exception('Selected message does not exist'); };

            //select the first support message
            $firstSupportMessage = $this->supportMessage::where('support_unique_id', $request->support_unique_id)->first();

            //check if the admin id has been filled and also if sender is an admin
            $this->updateAdmin($mainMessageObject, $userObject, $firstSupportMessage);

            $messageUniqueId = $this->createNewUniqueId('support_messages', 'unique_id', 20);
            $this->createMessageSupport($messageUniqueId, $request->support_unique_id, $request, $this->supportMessage);

            $this->saveSupportFile($filenameArray, $messageUniqueId);
            return Redirect::back()->with('success', 'Message was successfully submitted');
        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }

    private function updateAdmin($mainMessageObject, $userObject, $firstSupportMessage){
        if($mainMessageObject->admin_id === null && $userObject->type_of_user !== $this->user->normalUserType){
            $mainMessageObject->admin_id = $userObject->unique_id;
            $mainMessageObject->save();

            //update the initial support message
            $firstSupportMessage->reciever_id = $userObject->unique_id;
            $firstSupportMessage->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //unique_id user_id admin_id topic
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
    public function updateToClose($id)
    {
        try{
            //select the main message from main table
            $mainMessageObject = $this->support::where('unique_id', $id)->first();
            if($mainMessageObject === null){ throw new \Exception('Selected message does not exist'); };

            $mainMessageObject->status = $this->support->supportMessageClosedStatus;
            $mainMessageObject->save();

            return Redirect::back()->with('success', 'Support Ticket has been closed');
        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            //select the main message from main table
            $mainMessageObject = $this->support::where('unique_id', $id)->first();
            if($mainMessageObject === null){ throw new \Exception('Selected message does not exist'); }

            //select the suuport message
            $support_message_array = $mainMessageObject->support_message_array;
            $this->deleteSupportMessages($support_message_array);

            if($mainMessageObject->delete()){
                return Redirect::back()->with('success', 'Message was successfull submitted');
            }

        }catch(\Exception $exception){
            return response()->json([
                'status'=>false,
                'message'=>['general_error'=>[$exception->getMessage()]],
                'data'=>[]
            ]);
        }
    }

    private function deleteSupportMessages($support_message_array){
        if(count($support_message_array) > 0){
            foreach($support_message_array as $k => $eachMessage){
                $eachMessage->delete();
            }
        }
    }
}
