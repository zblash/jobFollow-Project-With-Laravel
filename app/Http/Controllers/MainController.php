<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Employee;
use App\Appointment;
use App\Service;
use App\Driver;
use App\User;
use App\Campaign;
use Validator;
use Response;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Modules\smsSenderFactory;
use App\Modules\callerFactory;
class MainController extends Controller
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
    
    public function index(){
        $customers = Customer::all()->count();
        $employees = Employee::where('confirmed',1)->get()->count();
        $appointments = Appointment::where('appointment_type','standard')->where('is_cancelled',0)->orderBy('appointment_time','desc')->limit(10)->get();
        $now = Carbon::now();
        $lastweek = $now->copy()->subDays(6);
        $lastmonth = $now->copy()->subDays(29);
        $weeklyprofit = Appointment::whereBetween('appointment_time',[$lastweek,$now])->sum('service_pay');
        $monthlyprofit = Appointment::whereBetween('appointment_time',[$lastmonth,$now])->sum('service_pay');
        return view('index',['customers' => $customers,'employees' => $employees,'appointments' => $appointments,
            'weeklyprofit' => $weeklyprofit,'monthlyprofit' => $monthlyprofit]);
    }
    public function customerAdd(Request $request){
        if($request->ajax()){
            $rules = [
            'name' => 'required|max:150|string',
            'phone' => 'required|min:10|string',
            'tc_no' => 'min:11|string',    
            'address' => 'required|string|max:150',
            'customer_level' => 'required'
        ];
        if($request->input('customer_level') == "kurumsal"){
            $rules['billing'] = 'required|string|max:150';
        }
        $messages = [
        'name.required' => 'Müşteri İsmi Boş Bırakılamaz{nt}',
        'phone.required'  => 'Müşteri Telefon Numarası Boş Bırakılamaz{nt}',
        'tc_no.required' => 'Müşteri T.C Kimlik No Boş Bırakılamaz{nt}',
        'address.required'  => 'Müşteri Adresi Boş Bırakılamaz{nt}',
        'address_direction.required' => 'Adres Tarifi Boş Bırakılamaz{nt}',
        'billing.required' => 'Müşteri Fatura Bilgisi Boş Bırakılamaz{nt}',
        'customer_level.required'  => 'Müşteri Tipi Boş Bırakılamaz{nt}'
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
            if(Customer::where('tc_no',$request->input('tc_no'))->count() > 0){
                return Response::json(array( 'messages' => 'Müşteri Zaten Kayıtlı'), 406);
            }
                $customer = new Customer();
                $customer->name = $request->input('name');
                $customer->phone = $request->input('phone');
                $customer->tc_no = $request->input('tc_no');
                $customer->address = $request->input('address');
                $customer->address_direction = $request->input('address_direction');
                if($request->input('customer_level') == 'bireysel'){
                    $customer->billing = 'Bireysel Müşteri';
                }else{
                $customer->billing = $request->input('billing');
                }
                if($request->has('mapscoordinates')){
                    $customer->address_coordinates = $request->input('mapscoordinates');
                }
                $customer->customer_level = $request->input('customer_level');
                $customer->save();
            return Response::json(array( 'messages' => 'Müşteri Başarıyla Eklendi'),200);
        
    
    }

}
    public function customerEdit(Request $request,$id){
        if($request->ajax()){
               $rules = [
            'name' => 'required|max:150|string',
            'phone' => 'required|min:10|string',
            'tc_no' => 'min:11|string',    
            'address' => 'required|string|max:150',
            'address_direction' => 'required',
            'customer_level' => 'required'
        ];
        if($request->input('customer_level') == "kurumsal"){
            $rules['billing'] = 'required|string|max:150';
        }
        $messages = [
        'name.required' => 'Müşteri İsmi Boş Bırakılamaz{nt}',
        'phone.required'  => 'Müşteri Telefon Numarası Boş Bırakılamaz{nt}',
        'address.required'  => 'Müşteri Adresi Boş Bırakılamaz{nt}',
        'address_direction.required' => 'Adres Tarifi Boş Bırakılamaz{nt}',
        'billing.required' => 'Müşteri Fatura Bilgisi Boş Bırakılamaz{nt}',
        'customer_level.required'  => 'Müşteri Tipi Boş Bırakılamaz{nt}'
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()));
            }
       try{
        $customer = Customer::findOrFail($id);
        $customertc = Customer::where('tc_no')->first();
        if($customertc != null){
        if ($customer->id != $customertc->id) {
            return Response::json(array( 'messages' => 'T.C No Başkasına Ait'),406);
        }
    }
                $customer->name = $request->input('name');
                $customer->phone = $request->input('phone');
                $customer->tc_no = $request->input('tc_no');
                $customer->address = $request->input('address');
                $customer->address_direction = $request->input('address_direction');
                if($request->input('customer_level') == 'bireysel'){
                    $customer->billing = 'Bireysel Müşteri';
                }else{
                $customer->billing = $request->input('billing');
                }
                if($request->has('mapscoordinates')){
                    $customer->address_coordinates = $request->input('mapscoordinates');
                }
                $customer->customer_level = $request->input('customer_level');
        $customer->save();
        return Response::json(array( 'messages' => 'Müşteri Bilgileri Güncellendi'));
    }
    catch(ModelNotFoundException $err){
        return Response::json(array( 'messages' => 'Beklenmeyen Bir Hata Oluştu'),406);
    }
        
    
    }

}
public function customersajax(){

    return Datatables::of(Customer::all())->
            addColumn('action', function ($user) {
                $txt = '<a href="musteri-ayrintilari/'.$user->id.'#pservices" class="btn btn-xs btn-primary"><i class="fa fa-calendar-plus-o"></i> Geçmiş Hizmetler</a>
                <a href="musteri-ayrintilari/'.$user->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                <a href="musteri-ayrintilari/'.$user->id.'#details" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Ayrıntılar</a>
                <a href="musteri-ayrintilari/'.$user->id.'#appointment" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Planlı Randevu</a>
                <button class="btn btn-xs btn-danger btn-delete" data-remote="musteri-sil/'.$user->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
                if($user->appointments()->exists()){
               $usercreated = new Carbon($user->appointments()->latest()->first()->created_at);
               $now = Carbon::now();
                if($usercreated->diffInHours($now) > 2){
                    $txt .= '<a href="personel-ata/'.$user->id.'" class="btn btn-xs btn-primary disabled"><i class="fa fa-user"></i> Personel Ata</a>';
                }else{
                    $txt .= '<a href="personel-ata/'.$user->id.'" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Personel Ata</a>';
                }
                }else{
                    $txt .= '<a href="personel-ata/'.$user->id.'" class="btn btn-xs btn-primary disabled"><i class="fa fa-user"></i> Personel Ata</a>';
                }
                return $txt;
                
            })->
        addColumn('created_t', function ($user) {
            $dt = new Carbon($user->created_at);

            return $dt->formatLocalized('%A %d %B %Y');;
            })->make(true);
}
public function customerdetail($id){
       try{
        $customer = Customer::findOrFail($id);
        $employees = Employee::where('confirmed',1)->get();
        $services = Service::all();
        $drivers = Driver::all();
        $srcm = array('(',')');
        $rpcm = array('','');
        if($customer->address_coordinates != ''){
        $customerexploder = explode(',', str_replace($srcm, $rpcm, $customer->address_coordinates));
        $mapslat = $customerexploder[0];
        $mapslng = $customerexploder[1]; 
        $mapstat = 1;
        }else{
        $mapslat = 0;
        $mapslng = 0;  
        $mapstat = 0;
        }
        
        return view('customers.customerProfile',['customer' => $customer,'id' => $id,'employees' => $employees,'services' => $services,'drivers' => $drivers,'mapslat' => $mapslat,'mapslng' => $mapslng,'mapstat' => $mapstat]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Müşteri Bulunamadi');
    }
}
public function customerajax($id){
     try{
        $customer = Customer::findOrFail($id);
        $p = $customer->appointments()->where('appointment_time','<' ,new Carbon())->where('is_cancelled',0)->get();
        return Datatables::of($customer->appointments()->where('appointment_time','<' ,new Carbon()))->
        addColumn('employees', function ($appointment) {
            $employees = '';
            foreach ($appointment->employees->all() as $employee) {
                $employees .= $employee->name.",";
            }
            $employees = rtrim($employees,','); 
                return $employees;
            })->
        addColumn('service', function ($appointment) {
            $services = '';
            foreach ($appointment->services->all() as $service) {
                $services .= $service->name.",";
            }
            $services = rtrim($services,','); 
                return $services;
            })->
        addColumn('appointment_date', function ($appointment) {
            $dt = new Carbon($appointment->appointment_time);

            return $dt->formatLocalized('%A %d %B %Y %H:%M');
            })->make(true);
        
    }
    catch(ModelNotFoundException $err){
        
    }
}


public function sendSms(Request $request){
    if($request->ajax()){
            $rules = [
            'message' => 'required|max:150|string',
        ];
        $messages = [
        'message.required' => 'Mesaj Boş Bırakılamaz{nt}',
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()));
            }
        $smsSenderFactory = new smsSenderFactory();
        $srcphone = array('(',')',' ','-');
        $rpcphone = array('','','','');
        if(in_array("smscustomer", $request->input('sms'))){
           $musteriler = Customer::all();
           $musteria = array();
           foreach ($musteriler as $musteri) {
               array_push($musteria, str_replace($srcphone, $rpcphone, $musteri->phone));
           }
            $smsSenderFactory->sendSMS($request->input('message') , $musteria);
        }
        if(in_array("smsdriver", $request->input('sms'))){
            $suruculer = Driver::all();
           $surucua = array();
           foreach ($suruculer as $surucu) {
               array_push($surucua, str_replace($srcphone, $rpcphone, $surucu->phone));
           }
            $smsSenderFactory->sendSMS($request->input('message') , $surucua);
        }
        if(in_array("smsemployee", $request->input('sms'))){
           $personeller = Employee::where('confirmed',1)->get();
           $personela = array();
           foreach ($personeller as $personel) {
               array_push($personela, str_replace($srcphone, $rpcphone, $personel->phone));
           }
            $smsSenderFactory->sendSMS($request->input('message') , $personela);
        }
        return Response::json(array( 'messages' => 'SMS Gönderimi Başarılı'),200); 
}
}
public function customerDestroy(Request $request,$id){
    $customer = Customer::find($id);
    $customer->delete();
}
public function adduserPost(Request $request){
     if($request->ajax()){
            $rules = [
            'name' => 'required|max:150',
            'email' => 'required|max:150|unique:users',
            'password' => 'required|min:4'
        ];
        $messages = [
        'name.required' => 'Yetkili İsmi Boş Bırakılamaz{nt}',
        'email.required' => 'Yetkili E-maili Boş Bırakılamaz{nt}',
        'password.required'  => 'Yetkili Şifresi Boş Bırakılamaz{nt}'
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()));
            }
       try{
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->remember_token = Str::random(60);
            $user->save();
        return Response::json(array( 'messages' => 'Yetkili Eklendi'),200);
    }
    catch(ModelNotFoundException $err){
        return Response::json(array( 'messages' => 'Beklenmeyen Bir Hata Oluştu'),500);
    }
        
    
    }

}
public function usersAjax(Request $request){
    return Datatables::of(User::all())->addColumn('action', function ($user) {
                return '<a href="yetkili-duzenle/'.$user->id.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>';
            })->make(true);
}
public function edituser($id){
          
       try{
            $user = User::findOrFail($id);
        return view('edituser',['user' => $user,'id' => $id]);
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Yetkili Bulunamadi.');
    }
        
}
public function edituserPost(Request $request,$id){
    if($request->ajax()){
                   $rules = [
            'name' => 'required|max:150',
            'email' => 'required|max:150'
        ];
        $messages = [
        'name.required' => 'Yetkili İsmi Boş Bırakılamaz{nt}',
        'email.required' => 'Yetkili E-maili Boş Bırakılamaz{nt}'
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),500);
            }
    try{
        $user = User::findOrFail($id);
         $user->name = $request->input('name');
            $user->email = $request->input('email');
            if($request->has('password')){
            $user->password = bcrypt($request->input('password'));
        }
            $user->save();
    return Response::json(array( 'messages' => 'Yetkili Güncellendi'),200);
    }
    catch(ModelNotFoundException $err){
        return Response::json(array( 'messages' => 'Beklenmeyen Bir Hata Oluştu'));
    }
}
}
public function addEmployeetoCustomer($id){
    try{
        $user = Customer::findOrFail($id);
        $appointment = $user->appointments()->latest()->first();
        $usercreated = new Carbon($appointment->created_at);
        $now = Carbon::now();
        if($usercreated->diffInHours($now) < 2){
            $employees = Employee::where('confirmed',1)->get();
            return view('customers.asignemployee',['id' => $id,'appointment' => $appointment,'employees' => $employees]);
        }else{
            return redirect()->back();
        }
    }catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Hata Oluştu.');
    }
}
public function addEmployeetoCustomerPost(Request $request,$id){
    if($request->ajax()){
            $rules = [
            'employee_id' => 'required'
        ];
       
       $messages = [
            'employee_id.required' => 'Personel Seçimi Boş Bırakılamaz{nt}' 
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()));
            }
        $user = Customer::findOrFail($id);
       $appointment = $user->appointments()->latest()->first();
         $appointment_time = new Carbon($appointment->appointment_time);
         $next_appointment_time = new Carbon($appointment->next_appointment_time);;
        $errors = "";
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        foreach ($employee->Appointments as $s) {
            if($s->id != $appointment->id){
            if($appointment_time == $s->appointment_time){
                $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }else if($next_appointment_time == $s->next_appointment_time){
                 $errors .= "".$employee->name." İsimli Personel Seçilen Tarihlerde veya Saatlerde meşgul.{nt}";
            }
        }
        }
    }

        if($errors != ""){
            return Response::json(array(
            'messages' => $errors
        ));
        }else{
            $servicestext = '';
            foreach ($appointment->services as $service) {
                $servicestext .= $service->name.",";
            }
            $servicestext = rtrim($servicestext,',');
            $appointment->employees()->detach();
            $srcphone = array('(',')',' ','-');
            $rpcphone = array('','','','');
            $employeesa = array();
            $appointment->employees()->detach();
            foreach ($request->input('employee_id') as $value) {
                $employee = Employee::find($value);
                $appointment->employees()->attach($employee->id);
                array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
            }
            $smsSenderFactory = new smsSenderFactory();
             $employeeMessage = "Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz. Bu tarih ve saat ile ilgili bir problem olursa ve temizliğe gelemeyecekseniz lütfen derhal 0 545 452 50 83 – 0 232 502 44 42 numaralı telefonu arayıp bilgi veriniz";
            $smsSenderFactory->sendSMS($employeeMessage , $employeesa);

            return Response::json(array(
                'messages' => 'Personel Ataması Yapıldı.'
            ),200);
        }

}
}

}