<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\DriverRequest;
use Validator;
use Yajra\Datatables\Datatables;
use App\Driver;
use App\Appointment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class DriverController extends Controller
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


    public function newdriverPost(DriverRequest $request){
    	Driver::create($request->all());
    	return redirect()->route('driversPage')->withSuccess("Şoför Eklendi");
    }

    public function driversajax(){
        return Datatables::of(Driver::all())->addColumn('action', function ($driver) {
                return '<a href="sofor-ayrintilari/'.$driver->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                <a href="sofor-ayrintilari/'.$driver->id.'#details" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Ayrıntılar</a>
                 <button class="btn btn-xs btn-danger btn-delete" data-remote="sofor-sil/'.$driver->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
            })->make(true);
    }

    public function driverDetails($id){
         try{
        $driver = Driver::findOrFail($id);
        return view('employees.driverProfile',['driver' => $driver,'id' => $id]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Şoför Bulunamadi');
    }
    }
    public function driverEdit(Request $request,$id){
         try{
         $rules = [
            'name' => 'required|max:150|string',
            'phone' => 'required|min:10|string',
            
        ];
        $messages = [
        'name.required' => 'Şoför İsmi Boş Bırakılamaz{nt}',
        'phone.required'  => 'Şoför Telefon Numarası Boş Bırakılamaz{nt}'
    ];
         $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                 return redirect()->back()->withErrors($validator->errors()->all());
            }
         $driver = Driver::findOrFail($id);
         $driver->name = $request->input('name');
         $driver->phone = $request->input('phone');
         $driver->save();
         return redirect()->route('driversPage')->withSuccess("Şoför Bilgileri Güncellendi");
    }catch(ModelNotFoundException $err){
         return redirect()->back()->withErrors("Beklenmeyen Bir Hata Oluştu");
    }
    }


    public function driverDestroy(Request $request,$id){
        $driver = Driver::find($id);
        
        $driver->delete();
    }


}
