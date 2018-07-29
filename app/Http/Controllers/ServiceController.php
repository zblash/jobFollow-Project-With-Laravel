<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use Validator;
use Response;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ServiceController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newservicePost(Request $request){
        if($request->ajax()){
             $rules = [
            'name' => 'required|string|unique:services'
        ];
       $messages = [
            'name.required' => 'Hizmet İsmi Boş Bırakılamaz'    
        ];


     $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
            Service::create($request->all());
                
            return Response::json(array( 'messages' => 'Hizmet Başarıyla Eklendi'),200);
               
            
        }
    }
    public function servicesajax(){
        return Datatables::of(Service::all())->addColumn('action', function ($service) {
                return '<a href="hizmet-duzenle/'.$service->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                 <button class="btn btn-xs btn-danger btn-delete" data-remote="hizmet-sil/'.$service->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
            })->make(true);
    }

    public function serviceDetails($id){
       try{
        $service = Service::findOrFail($id);
        return view('services.serviceProfile',['service' => $service,'id' => $id]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Hizmet Grubu Bulunamadi');
    }
    }

    public function serviceEdit(Request $request,$id){
                if($request->ajax()){
             $rules = [
            'name' => 'required|string'
        ];
       $messages = [
            'name.required' => 'Hizmet İsmi Boş Bırakılamaz'    
        ];


     $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
            try{
                $service = Service::findOrFail($id);
                $service->name = $request->input('name');
                $service->save();
                return Response::json(array( 'messages' => 'Hizmet Başarıyla Düzenlendi'),200);
                
            }
            catch(ModelNotFoundException $err){
                return Response::json(array( 'messages' => 'Beklenmeyen Bir Hata Oluştu.'),500);
            }
           
        }
    }

    public function serviceDestroy(Request $request,$id){
        $service = Service::find($id);
        $service->appointments()->detach();
        $service->delete();
    }
}
