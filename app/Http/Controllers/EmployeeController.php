<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Employee;
use App\Profit;
use App\Appointment;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Validator;
use Response;
use App\Modules\smsSenderFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class EmployeeController extends Controller
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


    public function newemployeePost(EmployeeRequest $request){
    	$photoName = '';
        if($request->hasFile('tc_pic')){
    	 $photoName = "personel".$request->input('tc_no').'.'.$request->tc_pic->getClientOriginalExtension();
         $request->tc_pic->move(public_path('pictures'), $photoName);
        }
    		Employee::create([
    			'name' => $request->input('name'),
    			'phone' => $request->input('phone'),
    			'address' => $request->input('address'),
    			'tc_no' => $request->input('tc_no'),
    			'tc_pic' => $photoName,
    			'bank' => $request->input('bank'),
    			'bank_account_owner' => $request->input('bankaccountowner'),
    			'iban' => $request->input('iban'),
    			'confirmed' => $request->input('confirmed')
    		]);
           
		
    	return redirect()->route('employeesPage')->withSuccess("Personel Kayıt Edildi");
    }
    public function employeesajax(){
          return Datatables::of(Employee::all())->addColumn('action', function ($employee) {
                return '<a href="personel-ayrintilari/'.$employee->id.'#pservices" class="btn btn-xs btn-primary"><i class="fa fa-calendar-plus-o"></i> Geçmiş Hizmetler</a>
                <a href="personel-ayrintilari/'.$employee->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                <a href="personel-ayrintilari/'.$employee->id.'#details" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Ayrıntılar</a>
                 <button class="btn btn-xs btn-danger btn-delete" data-remote="personel-sil/'.$employee->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
            })->
        addColumn('created_t', function ($employee) {
            $dt = new Carbon($employee->created_at);

            return $dt->formatLocalized('%A %d %B %Y');;
            })->
        addColumn('isconfirm', function ($appointment) {
             $c = "";
             if($appointment->confirmed == 1){
                $c = "Aktif";
            }else{
                $c = "Pasif";
            }
            return $c;
            })->make(true);
    }
    public function employeedetail($id){
       try{
        $employee = Employee::findOrFail($id);
        return view('employees.employeeProfile',['employee' => $employee,'id' => $id]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Personel Bulunamadi');
    }
}

public function employeeajax($id){
 try{
        $employee = Employee::findOrFail($id);

        return Datatables::of($employee->appointments->where('appointment_time','<',new Carbon()))->
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

            return $dt->formatLocalized('%A %d %B %Y %H:%M');;
            })->
        addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->make(true);
        
    }
    catch(ModelNotFoundException $err){
        
    }
}

public function employeeEdit(Request $request,$id){
    try{
         $rules = [
            'name' => 'required|max:150|string',
            'phone' => 'required|min:10|string',
            'tc_no' => 'min:11|string',    
            'bank' => 'string|max:150',
            'bankaccountowner' => 'string|max:150',
            'iban' => 'string|max:150',
            'confirmed' => 'required',
            'tc_pic' => 'image|mimes:jpg,png'
        ];
        $messages = [
        'name.required' => 'Personel İsmi Boş Bırakılamaz{nt}',
        'phone.required'  => 'Personel Telefon Numarası Boş Bırakılamaz{nt}',
        'confirmed.required'  => 'Personel Durumu Boş Bırakılamaz{nt}'
    ];
   $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                 return redirect()->back()->withErrors($validator->errors()->all());
            }
         
                $employee = Employee::findOrFail($id);

                $employee->name = $request->input('name');
                $employee->phone = $request->input('phone');
                $employee->address = $request->input('address');
                $employee->tc_no = $request->input('tc_no');
                if($request->hasFile('tc_pic')){
                    if($employee->tc_pic != null){
                    unlink($image_path);  
                    }
                 $photoName = "personel".$request->input('tc_no').'.'.$request->tc_pic->getClientOriginalExtension();
                 $image_path = public_path().'/pictures/'.$employee->tc_pic;
                 
                 $request->tc_pic->move(public_path('pictures'), $photoName);
                 $employee->tc_pic = $photoName;
                }
                $employee->bank = $request->input('bank');
                $employee->bank_account_owner = $request->input('bankaccountowner');
                $employee->iban = $request->input('iban');
                $employee->confirmed = $request->input('confirmed');
            $employee->save();
        
        return redirect()->route('employeesPage')->withSuccess("Personel Bilgileri Güncellendi");
    }catch(ModelNotFoundException $err){
         return redirect()->back()->withErrors("Beklenmeyen Bir Hata Oluştu");
    }
}
public function employeesPayPage(){
    Appointment::where('appointment_time', '<', new Carbon())
    ->where('is_employee_profit','=',0)
    ->update(['is_employee_profit' => 1]);
    return view('employees.employeesPay');
}
public function employeesPayAjax(){

 return Datatables::of(Employee::all())->
        addColumn('totalprofit', function ($employee) {
             $money = 0;
             foreach ($employee->appointments as $appointment) {
                if($appointment->is_employee_profit != 0)
                    $money += $appointment->employee_pay;
                 
             }
             return $money;
            })->
        addColumn('action', function ($employee) {
             return '<a href="personel-odeme-detaylari/'.$employee->id.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Ödeme Detayları</a>
                <a href="personel-gecmis-odemeler/'.$employee->id.'" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Geçmiş Ödemeler</a>
                <button class="btn btn-xs btn-danger btn-delete" data-remote="personel-odeme-yap/'.$employee->id.'"><i class="fa fa-trash"></i> Ödeme Yap</button>';
            })->make(true);
}
public function profitDetails($id){
    Appointment::where('appointment_time', '<', new Carbon())
    ->where('is_employee_profit',0)
    ->where('is_cancelled',0)
    ->update(['is_employee_profit' => 1]);
    return view('employees.profitDetails',['id' => $id]);
}
public function employeeprofitAjax($id){
    try{
    $employee = Employee::findOrFail($id);
    return Datatables::of($employee->appointments->where('is_employee_profit',1))->
        addColumn('service', function ($appointment) {
            $services = '';
            foreach ($appointment->services->all() as $service) {
                $services .= $service->name.",";
            }
            $services = rtrim($services,','); 
                return $services;
            })->
        addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->
        addColumn('appointment_d', function ($appointment) {

            $dt = new Carbon($appointment->appointment_time);
             return $dt->formatLocalized('%A %d %B %Y %H:%M');
            })->
        with('total', $employee->appointments->where('is_employee_profit',1)->sum('employee_pay'))->make(true);
    }catch(ModelNotFoundException $err){

    }
}
public function paytoEmployee(Request $request,$id){
    if ($request->ajax()) {
        try{
        $employee = Employee::findOrFail($id);
        $profit = $employee->appointments()->where('is_employee_profit','=',1)->sum('employee_pay');
        if($profit > 0){
        $employee->appointments()->where('is_employee_profit','=',1)
        ->update(['is_employee_profit' => 2]);
        Profit::create([
            'employee_id' => $employee->id,
            'profit' => $profit
        ]);
        $smsSenderFactory = new smsSenderFactory();
         $employeeMessage = "Sayın ".$employee->name." ".$profit." TL tutarındaki ödemeniz yapılmıştır.Lütfen banka hesabınızı kontrol edin.";
          $srcphone = array('(',')',' ','-');
        $rpcphone = array('','','','');
        $employeesa = array();
         array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
            $smsSenderFactory->sendSMS($employeeMessage , $employeesa);
        return Response::json(array( 'messages' => 'Ödeme Başarılı'));
    }else{
        return Response::json(array( 'messages' => 'Ödebilecek Tutar Yok'));
    }
    }catch(ModelNotFoundException $err){
       return Response::json(array( 'messages' => 'Beklenmeyen Bir Hata Oluştu'));
    }
    }
}
public function pastProfitforEmployeeAjax($id){
     try{
        return Datatables::of(Employee::findOrFail($id)->profits())->
        addColumn('employee', function ($profit) {
            return 'Banka - '. $profit->employee->bank.' | Hesap Sahibi - '.$profit->employee->bank_account_owner.' | IBAN - '.$profit->employee->iban;
            })->
        addColumn('pdate', function ($profit) {
            $dt = new Carbon($profit->created_at);

            return $dt->formatLocalized('%A %d %B %Y %H:%M');
            })->make(true);

    }catch(ModelNotFoundException $err){
      
    }
}
public function employeeDestroy(Request $request,$id){
    $employee = Employee::find($id);
    if ($employee->tc_pic != null) {
       $image_path = public_path().'/pictures/'.$employee->tc_pic;
    unlink($image_path);  
    }
    
    $employee->delete();
}


}
