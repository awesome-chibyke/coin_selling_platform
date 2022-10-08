<?php

namespace App\Http\Controllers\Front;

use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DisplayBanner;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DisplayBannerController extends Controller
{
    use Generics;

    function __construct(DisplayBanner $displayBanner){
        $this->displayBanner = $displayBanner;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $displayBannerArrray = $this->displayBanner::orderBy('id', 'DESC')->get();
        return view('logged.view_display_banner', ['display_banner'=>$displayBannerArrray, 'image_folder'=>$this->displayBanner->displayBannerFileStoragePath, 'display_banner_model_instance'=>$this->displayBanner]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logged.create_display_banner');
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
            'title' => ['nullable', 'string', 'min:8'],
            'description' => ['nullable', 'string', 'min:8'],
            'filename' => ['required', 'file', 'image', 'mimes:jpeg,bmp,png,jpg', 'max:3000000']
        ]);

        return $validator;
    }

    public function store(Request $request)
    {

        try{
            //validate inputs
            $validate = $this->validator($request->all());
            if($validate->fails()){
                $validate->getMessageBag();
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            //upload the image
            $filename = $this->uploadFile($request);

            if($filename !== null){

                //change the status of the main banner on display
                $this->deactivateDisplayBanner($this->displayBanner);

                $uniqueId = $this->createNewUniqueId('display_banners', 'unique_id', 20);//add the existing banner to the db
                $displayBannerObject = $this->createDisplayBanner($request, $filename, $this->displayBanner, $uniqueId);

                if($displayBannerObject){
                    return Redirect::back()->with('success', 'Request has been processed successfully');
                }
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('errors', $exception->getMessage());
        }

    }

    private function uploadFile($request){
        $filename = null;
        if($request->hasFile('filename')){//upload the file
            $filename = $this->saveFiles($request, $this->displayBanner->displayBannerFileStoragePath, $this->displayBanner->displayBannerDefaultFileName, 'filename', 'filename');
        }
        return $filename;
    }

    private function uploadFileForUpdate($request, $existFileObjectFromDb){
        $filename = null;
        if($request->hasFile('filename')){//upload the file
            $filename = $this->saveFiles($request, $this->displayBanner->displayBannerFileStoragePath, $this->displayBanner->displayBannerDefaultFileName, 'filename', 'filename', $existFileObjectFromDb);
        }
        return $filename;
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
    public function edit($uniqueId)
    {
        try{
            $displayBannerObject = $this->displayBanner::where('unique_id', $uniqueId)->first();
            if($displayBannerObject === null){ throw new \Exception('Banner record does not eist'); }

            return view('logged.edit_display_banner', ['display_banner'=>$displayBannerObject, 'image_folder'=>$this->displayBanner->displayBannerFileStoragePath]);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
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
        try{
            //validate inputs
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            //select the exxisting file
            $displayBannerObject = $this->displayBanner::where('unique_id', $id)->first();
            if($displayBannerObject === null){ throw new \Exception('Banner record does not eist'); }

            //upload the image
            $filename = $this->uploadFileForUpdate($request, $displayBannerObject);

            if($filename !== null){

                //add the existing banner to the db
                $request->filename = $filename;
                $updatedDisplayBannerObject = $this->updateDisplayBanner($request, $displayBannerObject);

                if($updatedDisplayBannerObject){
                    return Redirect::back()->with('success', 'Request has been updated successfully');
                }
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    function updateDisplayBannerToActive($uniqueId){

        try{

            $displayBannerObject = $this->displayBanner::where('unique_id', $uniqueId)->first();
            if($displayBannerObject === null){ throw new \Exception('Banner record does not eist'); }

            //change the status of the main banner on display
            $this->deactivateDisplayBanner($this->displayBanner);

            $request = (object)['status'=>$this->displayBanner->activeBannerDisplayStatus];
            $updatedDisplayBannerObject = $this->updateDisplayBanner($request, $displayBannerObject);
            if($updatedDisplayBannerObject){
                return Redirect::back()->with('success', 'Request has been updated successfully');
            }

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

            $displayBannerObject = $this->displayBanner::where('unique_id', $id)->first();
            if($displayBannerObject === null){ throw new \Exception('Banner record does not eist'); }

            if($displayBannerObject->delete()){
                return Redirect::back()->with('success', 'Data has been deleted successfully');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }
}