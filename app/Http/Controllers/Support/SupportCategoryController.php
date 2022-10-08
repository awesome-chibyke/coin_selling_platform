<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Support\SupportTicketCategory;
use App\Traits\Generics;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SupportCategoryController extends Controller
{
    use Generics;

    function __construct(SupportTicketCategory $supportTicketCategory)
    {
        $this->supportTicketCategory = $supportTicketCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supportTicketCategory = $this->supportTicketCategory::orderBy('id', 'DESC')->get();
        return view('logged.view_ticket_category', ['support_ticket_category'=>$supportTicketCategory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supportTicketCategory = $this->supportTicketCategory::orderBy('id', 'DESC')->get();

        return view('logged.add_support_ticket_category', ['support_ticket_category'=>$supportTicketCategory]);
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
            'category' => ['required', 'string', 'min:3', 'unique:support_ticket_categories'],
        ]);

        return $validator;
    }

    public function store(Request $request)
    {
        try{
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            //add the category to db
            $uniqueId = $this->createNewUniqueId('support_ticket_categories', 'unique_id', 20);
            $category = $this->supportTicketCategory;
            $category->unique_id =  $uniqueId;
            $category->category = $request->category;
            if($category->save()){
                return Redirect::back()->with('success', 'Category was successfully added');
            }
        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supportTicketCategory = $this->supportTicketCategory::where('unique_id', $id)->first();
        return view('logged.edit_ticket_category', ['support_ticket_category'=>$supportTicketCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function validatorForUpdate(array $data)
    {
        $validator =  Validator::make($data, [
            'category' => ['required', 'string', 'min:3'],
        ]);

        return $validator;
    }

    public function update(Request $request, $id)
    {
        try{
            $validate = $this->validatorForUpdate($request->all());
            if($validate->fails()){ return Redirect::back()->withErrors($validate->getMessageBag()); }

            //select the exist value
            $existingCategory = $this->supportTicketCategory::where('unique_id', $id)->first();
            if($existingCategory === null){ throw new \Exception('Selected category does not exist'); }

            //add the category to db
            $existingCategory->category = $request->category;
            if($existingCategory->save()){ return Redirect::back()->with('success', 'Category was successfully updated'); }

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

            $existingCategory = $this->supportTicketCategory::where('unique_id', $id)->first();
            if($existingCategory === null){ throw new \Exception('Selected category does not exist'); }

            //check the support and delete all the supports under this category
            $main_support = $existingCategory->main_support;
            $mainSupportMessageArray = []; $filesObjectArray = [];
            if(count($main_support) > 0){
                foreach($main_support as $k => $eachSupport){
                    $mainSupportMessageArray = count($eachSupport->support_message_array) > 0 ? array_merge($mainSupportMessageArray, $eachSupport->support_message_array) : $mainSupportMessageArray;

                    //select the files and also delete
                    $eachSupport->delete();
                }

                if(count($mainSupportMessageArray) > 0){
                    foreach($mainSupportMessageArray as $l => $eachSupportMessage){
                        $filesObjectArray = count($eachSupportMessage->support_files_array) > 0 ? array_merge($filesObjectArray, $eachSupportMessage->support_files_array) : $filesObjectArray;
                        $eachSupportMessage->delete();
                    }
                }

                if(count($filesObjectArray) > 0){
                    foreach($filesObjectArray as $l => $eachFileObject){
                        $eachFileObject->delete();
                    }
                }
            }

            if($existingCategory->delete()){ return Redirect::back()->with('success', 'Ticket category was successfully deleted'); }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }
}
