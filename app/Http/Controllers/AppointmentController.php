<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Employee;
use App\Customer;
use App\Appointment;
use App\Service;
use App\Driver;
use App\Profit;
use App\Satisfaction;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Modules\smsSenderFactory;
use App\Modules\callerFactory;
class AppointmentController extends Controller
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

    public function newappointmentPage(){
    	$customers = Customer::all();
    	$employees = Employee::where('confirmed',1)->get();
    	$services = Service::all();
    	$drivers = Driver::all();
    	return view('appointments.newappointment',['customers' => $customers,'employees' => $employees,'services' => $services,'drivers' => $drivers])->with('success', 'Profile updated!');;
    }
        public function newplannedappointmentPage(){
        $customers = Customer::all();
        $employees = Employee::where('confirmed',1)->get();
        $services = Service::all();
        $drivers = Driver::all();
        return view('appointments.newplannedappointment',['customers' => $customers,'employees' => $employees,'services' => $services,'drivers' => $drivers])->with('success', 'Profile updated!');;
    }
    public function newappointmentPost(Request $request){

        if($request->ajax()){
            $rules = [
            'customer_id' => 'required',
            'service_id' => 'required',
            'service_pay' => 'required|numeric',
            'employee_pay' => 'required|numeric',  
            'employee_id' => 'required',
            'driver_id' => 'required',
            'payment_type' => 'required',
            'payment_type' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required'
        ];
        if($request->input('customer_id') == -1){
            $rules['name'] = 'required|max:150|string';
            $rules['phone'] = 'required|min:10|string';
            $rules['tc_no'] = 'min:11|string';    
            $rules['address'] = 'required|string|max:150';
            if($request->input('customer_level') == "kurumsal"){
            $rules['billing'] = 'required|string|max:150';
            }
            $rules['customer_level'] = 'required'; 
        }
       $messages = [
                'name.required' => 'Müşteri İsmi Boş Bırakılamaz{nt}',
                'phone.required'  => 'Müşteri Telefon Numarası Boş Bırakılamaz{nt}',
                'tc_no.required' => 'Müşteri T.C Kimlik No Boş Bırakılamaz{nt}',
                'address.required'  => 'Müşteri Adresi Boş Bırakılamaz{nt}',
                'billing.required' => 'Müşteri Fatura Bilgisi Boş Bırakılamaz{nt}',
                'customer_level.required'  => 'Müşteri Tipi Boş Bırakılamaz{nt}',
                'service_id.required' => 'Hizmet Seçimi Boş Bırakılamaz{nt}',
                'service_pay.required' => 'Hizmet Ücreti Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'employee_pay.required' => 'Personel Ücreti Boş Bırakılamaz{nt}',  
                'employee_id.required' => 'Personel Seçimi Boş Bırakılamaz{nt}',
                'driver_id.required' => 'Şoför Seçimi Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'appointment_date.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_time.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'sms.required' => 'SMS Boş Bırakılamaz{nt}'     
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
         $appointment_time = new Carbon($request->input('appointment_date').' '.$request->input('appointment_time'));
        $errors = "";
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        foreach ($employee->Appointments as $s) {
            if($appointment_time == $s->appointment_time){
                $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }
        }
    }

        if($errors != ""){
            return Response::json(array(
            'messages' => $errors
        ),406);
        }else{
        
    	$customerid;
        $customer;
    	if($request->input('customer_id') == -1){
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
    		$customerid = $customer->id;
    	}else{
    		$customerid = $request->input('customer_id');
          
            $customer = Customer::findOrFail($customerid);
    	}
    	
    	$appointment = Appointment::create([
    		'customer_id' => $customerid,
    		'appointment_type' => 'standard',
    		'service_pay' => $request->input('service_pay'),
    		'employee_pay' => $request->input('employee_pay'),
            'payment_type' => $request->input('payment_type'),
    		'driver_id' => $request->input('driver_id'),
    		'appointment_time' => $appointment_time,
    		'next_appointment_time' => $appointment_time,
            'appointment_range' => 0,
            'is_employee_profit' => 0
    	]);
       
        $satisfaction = Satisfaction::create([
            'customer_id' => $customerid,
            'appointment_id' => $appointment->id,
            'status' => 0,
            'is_controlled' => 0,
            'appointment_date' => $appointment_time,
            'bulkid' => 0,
            'control_counter' => 0
        ]);
        $srcphone = array('(',')',' ','-');
        $rpcphone = array('','','','');
        $employeesa = array();
        $dt = new Carbon($appointment_time);
        $employeenametext = '';
        $servicestext = '';
    	foreach ($request->input('employee_id') as $value) {
    	$employee = Employee::where('id',$value)->first();
    	$appointment->employees()->attach($employee->id);
        $employeenametext .= $employee->name.", ";
        array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
        
        }
        foreach ($request->input('service_id') as $value) {
            $service = Service::where('id',$value)->first();
            $appointment->services()->attach($service->id);
            $servicestext .= $service->name.", ";
        }
        $servicestext = rtrim($servicestext,','); 
        $employeenametext = rtrim($employeenametext,','); 
        $googlemapslink = "";
            if($request->has('mapscoordinates')){
                $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$request->input('mapscoordinates'));
            }else{
                if($customer->address_coordinates != null){
                    $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$customer->address_coordinates);
                }
            }
        if( $request->has('sms') ){
            
        $smsSenderFactory = new smsSenderFactory();
        if(in_array("smscustomer", $request->input('sms'))){
            $musteria = array();
            array_push($musteria, str_replace($srcphone, $rpcphone,$customer->phone));
            $SMSmessage = "Sayın ".$customer->name.", ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için randevunuz başarı ile oluşturulmuştur. Ödeme Yapacağınız Tutar : ".$appointment->service_pay."’dir.";
            $smsSenderFactory->sendSMS($SMSmessage , $musteria);
        }
        if(in_array("smsdriver", $request->input('sms'))){
            $driver = Driver::findOrFail($request->input('driver_id'));
            $drivera = array();
            array_push($drivera, str_replace($srcphone, $rpcphone,$driver->phone));

            $SMSmessage = "Merhaba ".$driver->name." ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')."‘da ".$customer->address." adresinde ".$servicestext." için, ".$employeenametext." personeller bırakılacaktır. Müşteriden tahsil edilecek tutar : ".$request->input('service_pay')." TL’dir. Ödeme Türü : ".$request->input('payment_type')." ".$customer->name." : Müşteri yol tarifi : ".$customer->address_direction." ".$googlemapslink;
            $smsSenderFactory->sendSMS($SMSmessage , $drivera);
        }
        if(in_array("smsemployee", $request->input('sms'))){
           $SMSmessage = "Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz.";
            $smsSenderFactory->sendSMS($SMSmessage , $employeesa);
        }
        }
    	   return Response::json(array(
            'messages' => 'Randevu Oluşturuldu.'
        ));
        }
    }
    }
    public function denemem(){
         $satisfaction = Satisfaction::where("is_controlled",1)->groupBy('bulkid')->get();
         foreach ($satisfaction as $value) {
             echo $value->bulkid." id".$value->id."<br>";
         }
    }
    public function plannedappointmentEdit(Request $request,$id){
        if($request->ajax()){
            $rules = [
            'customer_id' => 'required',
            'service_id' => 'required',
            'service_pay' => 'required|numeric',
            'employee_pay' => 'required|numeric',  
            'payment_type' => 'required',
            'driver_id' => 'required',
            'payment_type' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'appointment_range' => 'required|numeric'
        ];
       $messages = [
                'customer_id.required' => 'Müşteri Seçimi Boş Bırakılamaz{nt}',
                'service_id.required' => 'Hizmet Seçimi Boş Bırakılamaz{nt}',
                'service_pay.required' => 'Hizmet Ücreti Boş Bırakılamaz{nt}',
                'employee_pay.required' => 'Personel Ücreti Boş Bırakılamaz{nt}',  
                 'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'driver_id.required' => 'Şoför Seçimi Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'appointment_date.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_time.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_range.required' => 'Randevu Sıklığı Alanı Boş Bırakılamaz{nt}',
                'sms.required' => 'SMS Boş Bırakılamaz{nt}'     
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
         $appointment_time = new Carbon($request->input('appointment_date').' '.$request->input('appointment_time'));
         $next_appointment_time = $appointment_time->copy()->addDays($request->input('appointment_range'));
        $errors = "";
        if($request->has('employee_id')){
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        foreach ($employee->Appointments as $s) {
            if($s->id != $id){
            if($appointment_time == $s->appointment_time){
                $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }else if($next_appointment_time == $s->next_appointment_time){
                 $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }
        }
        }
    }
}
        if($errors != ""){
            return Response::json(array(
            'messages' => $errors
        ),406);
        }else{
            
            $customerid = $request->input('customer_id');
            $customer = Customer::findOrFail($customerid);
            $appointment = Appointment::findOrFail($id);
            $appointment->employees()->detach();
            $appointment->services()->detach();
            $appointment->customer_id = $customerid;
            $appointment->appointment_type = 'planned';
            $appointment->service_pay = $request->input('service_pay');
            $appointment->employee_pay = $request->input('employee_pay');
            $appointment->driver_id = $request->input('driver_id');
            $appointment->payment_type = $request->input('payment_type');
            $appointment->appointment_time = $appointment_time;
            $appointment->next_appointment_time = $next_appointment_time;
            $appointment->appointment_range = $request->input('appointment_range');
            $appointment->save();
        $appointment_range = '';
        if($request->input('appointment_range') == 1){
            $appointment_range = "GUNLUK olarak";
        }
        if($request->input('appointment_range') == 5){
           $appointment_range = "5 günde bir defa olarak";     
        }
        if($request->input('appointment_range') == 7){
            $appointment_range = "HAFTALIK olarak";
        }
        if($request->input('appointment_range') == 14){
            $appointment_range = "2 haftada bir defa olarak";
        }
        if($request->input('appointment_range') == 30){
            $appointment_range = "AYLIK olarak";
        }
        
        $srcphone = array('(',')',' ','-');
        $rpcphone = array('','','','');
        $employeesa = array();
        $employeenames = array();
        $employeenametext = '';
        $dt = new Carbon($appointment_time);
        $servicestext = '';
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        $appointment->employees()->attach($employee->id);
        $employeenametext .= $employee->name.", ";
        array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
        
        }
        if ($request->has('employee_id')) {
        foreach ($request->input('service_id') as $value) {
            $service = Service::where('id',$value)->first();
            $appointment->services()->attach($service->id);
            $servicestext .= $service->name.", ";
        }
         }
        $employeenametext = rtrim($employeenametext,','); 
        $servicestext = rtrim($servicestext,',');
        $googlemapslink = "";
            if($request->has('mapscoordinates')){
                $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$request->input('mapscoordinates'));
            }else{
                if($customer->address_coordinates != null){
                    $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$customer->address_coordinates);
                }
            }
        if( $request->has('sms') ){
        $smsSenderFactory = new smsSenderFactory();
        if(in_array("smscustomer", $request->input('sms'))){
            $musteria = array();
            array_push($musteria, str_replace($srcphone, $rpcphone,$customer->phone));
            $SMSmessage = "(DUZELTME)Sayın ".$customer->name.", ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için randevunuz başarı ile oluşturulmuştur.Randevunuz ".$appointment_range." tekrarlanacaktır. Ödeme Yapacağınız Tutar : ".$appointment->service_pay."’dir.";
            $smsSenderFactory->sendSMS($SMSmessage , $musteria);
        }
        if(in_array("smsdriver", $request->input('sms'))){
            $driver = Driver::findOrFail($request->input('driver_id'));
            $drivera = array();
            array_push($drivera, str_replace($srcphone, $rpcphone,$driver->phone));

            $SMSmessage = "(DUZELTME)Merhaba ".$driver->name." ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')."‘da ".$customer->address." adresinde ".$servicestext." için, ".$employeenametext." personeller bırakılacaktır. Müşteriden tahsil edilecek tutar : ".$request->input('service_pay')." TL’dir. Ödeme Türü : ".$request->input('payment_type')." ".$customer->name." : Müşteri yol tarifi : ".$customer->address_direction." ".$googlemapslink;
            $smsSenderFactory->sendSMS($SMSmessage , $drivera);
        }
        if(in_array("smsemployee", $request->input('sms')) and $request->has('employee_id')){
           $SMSmessage = "(DUZELTME)Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz.";
            $smsSenderFactory->sendSMS($SMSmessage , $employeesa);
        }
        }
           return Response::json(array(
            'messages' => 'Planlı Randevu Güncellendi'
        ));
        }
    }
    }
    public function newplannedappointmentPost(Request $request){

        if($request->ajax()){
            $rules = [
            'customer_id' => 'required',
            'service_id' => 'required',
            'service_pay' => 'required|numeric',
            'employee_pay' => 'required|numeric',  
            'payment_type' => 'required',
            'driver_id' => 'required',
            'payment_type' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'appointment_range' => 'required|numeric'
        ];
       $messages = [
                'customer_id.required' => 'Müşteri Seçimi Boş Bırakılamaz{nt}',
                'service_id.required' => 'Hizmet Seçimi Boş Bırakılamaz{nt}',
                'service_pay.required' => 'Hizmet Ücreti Boş Bırakılamaz{nt}',
                'employee_pay.required' => 'Personel Ücreti Boş Bırakılamaz{nt}',  
                 'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'driver_id.required' => 'Şoför Seçimi Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'appointment_date.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_time.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_range.required' => 'Randevu Sıklığı Alanı Boş Bırakılamaz{nt}',
                'sms.required' => 'SMS Boş Bırakılamaz{nt}'     
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
         $appointment_time = new Carbon($request->input('appointment_date').' '.$request->input('appointment_time'));
         $next_appointment_time = $appointment_time->copy()->addDays($request->input('appointment_range'));
        $errors = "";
        if($request->has('employee_id')){
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        foreach ($employee->Appointments as $s) {
            if($appointment_time == $s->appointment_time){
                $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }else if($next_appointment_time == $s->next_appointment_time){
                 $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }
        }
    }
}
        if($errors != ""){
            return Response::json(array(
            'messages' => $errors
        ),406);
        }else{
            $customerid = $request->input('customer_id');
            $customer = Customer::findOrFail($customerid);
        $appointment = Appointment::create([
            'customer_id' => $customerid,
            'appointment_type' => 'planned',
            'service_pay' => $request->input('service_pay'),
            'employee_pay' => $request->input('employee_pay'),
            'driver_id' => $request->input('driver_id'),
            'payment_type' => $request->input('payment_type'),
            'appointment_time' => $appointment_time,
            'next_appointment_time' => $next_appointment_time,
            'appointment_range' => $request->input('appointment_range')
        ]);
        $appointment_range = '';
        if($request->input('appointment_range') == 1){
            $appointment_range = "GUNLUK olarak";
        }
        if($request->input('appointment_range') == 5){
           $appointment_range = "5 günde bir defa olarak";     
        }
        if($request->input('appointment_range') == 7){
            $appointment_range = "HAFTALIK olarak";
        }
        if($request->input('appointment_range') == 14){
            $appointment_range = "2 haftada bir defa olarak";
        }
        if($request->input('appointment_range') == 30){
            $appointment_range = "AYLIK olarak";
        }
        $satisfaction = Satisfaction::create([
            'customer_id' => $customerid,
            'appointment_id' => $appointment->id,
            'status' => 0,
            'is_controlled' => 0,
            'appointment_date' => $appointment_time,
            'bulkid' => 0,
            'control_counter' => 0
        ]);
        $srcphone = array('(',')',' ','-');
        $rpcphone = array('','','','');
        $employeesa = array();
        $employeenames = array();
        $dt = new Carbon($appointment_time);
        $employeenametext = '';
        $servicestext = '';
        if ($request->has('employee_id')) {
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        $appointment->employees()->attach($employee->id);
        $employeenametext .= $employee->name.", ";
        array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
        
        }
        }
        
        foreach ($request->input('service_id') as $value) {
            $service = Service::where('id',$value)->first();
            $appointment->services()->attach($service->id);
            $servicestext .= $service->name.", ";
        
        }
        if ($request->has('employee_id')) {
        $servicestext = rtrim($servicestext,',');
    }
        $googlemapslink = "";
            if($request->has('mapscoordinates')){
                $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$request->input('mapscoordinates'));
            }else{
                if($customer->address_coordinates != null){
                    $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$customer->address_coordinates);
                }
            }
        if( $request->has('sms') ){
        $smsSenderFactory = new smsSenderFactory();
        if(in_array("smscustomer", $request->input('sms'))){
            $musteria = array();
            array_push($musteria, str_replace($srcphone, $rpcphone,$customer->phone));
            $SMSmessage = "Sayın ".$customer->name.", ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için randevunuz başarı ile oluşturulmuştur.Randevunuz ".$appointment_range." tekrarlanacaktır. Ödeme Yapacağınız Tutar : ".$appointment->service_pay."’dir.";
            $smsSenderFactory->sendSMS($SMSmessage , $musteria);
        }
        if(in_array("smsdriver", $request->input('sms'))){
            $driver = Driver::findOrFail($request->input('driver_id'));
            $drivera = array();
            array_push($drivera, str_replace($srcphone, $rpcphone,$driver->phone));

            $SMSmessage = "Merhaba ".$driver->name." ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')."‘da ".$customer->address." adresinde ".$servicestext." için, ".$employeenametext." personeller bırakılacaktır. Müşteriden tahsil edilecek tutar : ".$request->input('service_pay')." TL’dir. Ödeme Türü : ".$request->input('payment_type')." ".$customer->name." : Müşteri yol tarifi : ".$customer->address_direction." ".$googlemapslink;
            $smsSenderFactory->sendSMS($SMSmessage , $drivera);
        }
        if(in_array("smsemployee", $request->input('sms')) and $request->has('employee_id')){
            $employeenametext = rtrim($employeenametext,','); 
           $SMSmessage = "Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz.";
            $smsSenderFactory->sendSMS($SMSmessage , $employeesa);
        }
        }
           return Response::json(array(
            'messages' => 'Planlı Randevu Oluşturuldu'
        ));
        }
    }
    }
    public function cancelledappointmentsAjax(){
        $appointments = appointment::where('is_cancelled',1)->get();
         return Datatables::of($appointments)->
         addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->
         addColumn('customerphone', function ($appointment) {
             return $appointment->customer->phone;
            })->
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

            return $dt->formatLocalized('%A %d %B %Y %H:%M');;
            })->make(true);
    }
    public function appointmentsajax(){
        $appointments = appointment::where('appointment_type','standard')->where('is_cancelled',0)->get();
         return Datatables::of($appointments)->
         addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->
         addColumn('customerphone', function ($appointment) {
             return $appointment->customer->phone;
            })->
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

            return $dt->formatLocalized('%A %d %B %Y %H:%M');;
            })->
            addColumn('action', function ($appointment) {
                return '<a href="randevu-duzenle/'.$appointment->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                <button class="btn btn-xs btn-danger btn-warning" data-remote="randevu-iptal/'.$appointment->id.'"><i class="fa fa-trash"></i> İptal Et</button>
                <button class="btn btn-xs btn-danger btn-delete" data-remote="randevu-sil/'.$appointment->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
            })->make(true);
    }
    public function plannedappointmentsajax(){
        $appointments = Appointment::where('appointment_type','planned')->where('is_cancelled','=',0)->get();
        $bugun = Carbon::now();
            foreach ($appointments as $appo) {
                if($bugun > $appo->appointment_time){
                   $ads = new Carbon($appo->next_appointment_time);
                    $appo->appointment_time = $appo->next_appointment_time;
                    $appo->next_appointment_time = $ads->copy()->addDays($appo->appointment_range);
                    $appo->save();
                }
            }
         return Datatables::of(Appointment::where('appointment_type','planned')->where('is_cancelled',0))->
         addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->
         addColumn('customerphone', function ($appointment) {
             return $appointment->customer->phone;
            })->
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
             $now = Carbon::now();
            $txt = '';
            if($now->diffInDays($dt) < 7){
               $txt = '<p style="color:red">'.$dt->diffForHumans($now).' - '.$dt->formatLocalized('%A %d %B %Y %H:%M').'</p>';
            }else{
                 $txt = '<p>'.$dt->diffForHumans($now).' - '.$dt->formatLocalized('%A %d %B %Y %H:%M').'</p>';
            }

            return $txt;
            })->
        addColumn('next_appointment_date', function ($appointment) {

            $dt = new Carbon($appointment->next_appointment_time);
            $now = Carbon::now();
            $txt = '';
            if($now->diffInDays($dt) < 7){
               $txt = '<p style="color:red">'.$dt->diffForHumans($now).' - '.$dt->formatLocalized('%A %d %B %Y %H:%M').'</p>';
            }else{
                 $txt = '<p>'.$dt->diffForHumans($now).' - '.$dt->formatLocalized('%A %d %B %Y %H:%M').'</p>';
            }

            return $txt;
            })->
            addColumn('action', function ($appointment) {
                return '<a href="planli-randevu-duzenle/'.$appointment->id.'#edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
                <a href="planli-randevu-personel-ata/'.$appointment->id.'" class="btn btn-xs btn-primary"><i class="fa fa-user"></i> Personel Ata</a>
                <button class="btn btn-xs btn-danger btn-warning" data-remote="randevu-iptal/'.$appointment->id.'"><i class="fa fa-trash"></i> İptal Et</button>
                <button class="btn btn-xs btn-danger btn-delete" data-remote="randevu-sil/'.$appointment->id.'"><i class="fa fa-trash"></i> Sil</button>
                ';
            })->escapeColumns([])->make(true);
    }
    public function appointmentPage($id){
                try{
        $appointment = Appointment::findOrFail($id);
        $customers = Customer::all();
        $employees = Employee::where('confirmed',1)->get();
        $services = Service::all();
        $drivers = Driver::all();
        $srcm = array('(',')');
        $rpcm = array('','');
         if($appointment->customer->address_coordinates != ''){
        $customerexploder = explode(',', str_replace($srcm, $rpcm, $appointment->customer->address_coordinates));
        $mapslat = $customerexploder[0];
        $mapslng = $customerexploder[1]; 
        $mapstat = 1;
        }else{
        $mapslat = 0;
        $mapslng = 0;  
        $mapstat = 0;
        }
        $appointment_d = Carbon::parse($appointment->appointment_time);
        $appointment_date = $appointment_d->year.'-'.$appointment_d->month.'-'.$appointment_d->day;
        $appointment_time = $appointment_d->hour.':'.$appointment_d->minute;
        return view('appointments.appointmentEdit',['appointment' => $appointment,'id' => $id, 'customers' => $customers,'employees' => $employees,'services' => $services,'drivers' => $drivers,'appointment_date' => $appointment_date,'appointment_time' => $appointment_time,'mapslat' => $mapslat,'mapslng' => $mapslng,'mapstat' => $mapstat]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Müşteri Bulunamadi');
    }
    }
    public function plannedappointmentPage($id){
                try{
        $appointment = Appointment::findOrFail($id);
        $customers = Customer::all();
        $employees = Employee::where('confirmed',1)->get();
        $services = Service::all();
        $drivers = Driver::all();
        $srcm = array('(',')');
        $rpcm = array('','');
        if($appointment->customer->address_coordinates != ''){
        $customerexploder = explode(',', str_replace($srcm, $rpcm, $appointment->customer->address_coordinates));
        $mapslat = $customerexploder[0];
        $mapslng = $customerexploder[1]; 
        $mapstat = 1;
        }else{
        $mapslat = 0;
        $mapslng = 0;  
        $mapstat = 0;
        }
        $appointment_d = Carbon::parse($appointment->appointment_time);
        $appointment_date = $appointment_d->year.'-'.$appointment_d->month.'-'.$appointment_d->day;
        $appointment_time = $appointment_d->hour.':'.$appointment_d->minute;
        return view('appointments.plannedappointmentEdit',['appointment' => $appointment,'id' => $id, 'customers' => $customers,'employees' => $employees,'services' => $services,'drivers' => $drivers,'appointment_date' => $appointment_date,'appointment_time' => $appointment_time,'mapslat' => $mapslat,'mapslng' => $mapslng,'mapstat' => $mapstat]); 
        
    }
    catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Müşteri Bulunamadi');
    }
    }

    public function appointmentEdit(Request $request,$id){
   if($request->ajax()){
            $rules = [
            'customer_id' => 'required',
            'service_id' => 'required',
            'service_pay' => 'required|numeric',
            'employee_pay' => 'required|numeric',  
            'employee_id' => 'required',
            'payment_type' => 'required',
            'driver_id' => 'required',
            'payment_type' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required'
        ];
        if($request->input('customer_id') == -1){
            $rules['name'] = 'required|max:150|string';
            $rules['phone'] = 'required|min:10|string';
            $rules['tc_no'] = 'required|min:11|string';    
            $rules['address'] = 'required|string|max:150';
            $rules['billing'] = 'required|string|max:150';
            $rules['customer_level'] = 'required'; 
        }
       $messages = [
                'name.required' => 'Müşteri İsmi Boş Bırakılamaz{nt}',
                'phone.required'  => 'Müşteri Telefon Numarası Boş Bırakılamaz{nt}',
                'tc_no.required' => 'Müşteri T.C Kimlik No Boş Bırakılamaz{nt}',
                'address.required'  => 'Müşteri Adresi Boş Bırakılamaz{nt}',
                'billing.required' => 'Müşteri Fatura Bilgisi Boş Bırakılamaz{nt}',
                'customer_level.required'  => 'Müşteri Tipi Boş Bırakılamaz{nt}',
                'service_id.required' => 'Hizmet Seçimi Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'service_pay.required' => 'Hizmet Ücreti Boş Bırakılamaz{nt}',
                'employee_pay.required' => 'Personel Ücreti Boş Bırakılamaz{nt}',  
                'employee_id.required' => 'Personel Seçimi Boş Bırakılamaz{nt}',
                'payment_type.required' => 'Ödeme Türü Boş Bırakılamaz{nt}',
                'driver_id.required' => 'Şoför Seçimi Boş Bırakılamaz{nt}',
                'appointment_date.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'appointment_time.required' => 'Tarih Alanı Boş Bırakılamaz{nt}',
                'sms.required' => 'SMS Boş Bırakılamaz{nt}'     
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return Response::json(array( 'messages' => $validator->errors()->all()),406);
            }
         $appointment_time = new Carbon($request->input('appointment_date').' '.$request->input('appointment_time'));
        $errors = "";
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        foreach ($employee->Appointments as $s) {
            if($s->id != $id){
            if($appointment_time == $s->appointment_time){
                $errors .= "".$employee->name." İsimli Personel Seçilen Tarih veya Saatde meşgul.{nt}";
            }
        }
        }
    }

        if($errors != ""){
            return Response::json(array(
            'messages' => $errors
        ),406);
        }else{
        try{
            $customerid = $request->input('customer_id');
            $customer = Customer::findOrFail($customerid);
            $appointment = Appointment::findOrFail($id);
            $appointment->employees()->detach();
            $appointment->services()->detach();
            $appointment->customer_id = $customerid;
            $appointment->appointment_type = 'standard';
            $appointment->service_pay = $request->input('service_pay');
            $appointment->employee_pay = $request->input('employee_pay');
            $appointment->driver_id = $request->input('driver_id');
            $appointment->payment_type = $request->input('payment_type');
            $appointment->appointment_time = $appointment_time;
            $appointment->next_appointment_time = $appointment_time;
            $appointment->appointment_range = 0;
            $appointment->save();
            $srcphone = array('(',')',' ','-');
            $rpcphone = array('','','','');
            $employeesa = array();
            $employeenametext ='';
            $dt = new Carbon($appointment_time);
            $servicestext = '';
        foreach ($request->input('employee_id') as $value) {
        $employee = Employee::where('id',$value)->first();
        $appointment->employees()->attach($employee->id);
        $employeenametext .= $employee->name.", ";
        array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
        
        }
        foreach ($request->input('service_id') as $value) {
            $service = Service::where('id',$value)->first();
            $appointment->services()->attach($service->id);
            $servicestext .= $service->name.", ";
        }
        $servicestext = rtrim($servicestext,','); 
        $employeenametext = rtrim($employeenametext,','); 
        $googlemapslink = "";
            if($request->has('mapscoordinates')){
                $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$request->input('mapscoordinates'));
            }else{
                if($customer->address_coordinates != null){
                    $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$customer->address_coordinates);
                }
            }
        if( $request->has('sms') ){
            
        $smsSenderFactory = new smsSenderFactory();
        if(in_array("smscustomer", $request->input('sms'))){
            $musteria = array();
            array_push($musteria, str_replace($srcphone, $rpcphone,$customer->phone));
            $SMSmessage = "(DUZELTME)Sayın ".$customer->name.", ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için randevunuz başarı ile oluşturulmuştur. Ödeme Yapacağınız Tutar : ".$appointment->service_pay."’dir.";
            $smsSenderFactory->sendSMS($SMSmessage , $musteria);
        }
        if(in_array("smsdriver", $request->input('sms'))){
            $driver = Driver::findOrFail($request->input('driver_id'));
            $drivera = array();
            array_push($drivera, str_replace($srcphone, $rpcphone,$driver->phone));

            $SMSmessage = "(DUZELTME)Merhaba ".$driver->name." ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')."‘da ".$customer->address." adresinde ".$servicestext." için, ".$employeenametext." personeller bırakılacaktır. Müşteriden tahsil edilecek tutar : ".$request->input('service_pay')." TL’dir. Ödeme Türü : ".$request->input('payment_type')." ".$customer->name." : Müşteri yol tarifi : ".$customer->address_direction." ".$googlemapslink;
            $smsSenderFactory->sendSMS($SMSmessage , $drivera);
        }
        if(in_array("smsemployee", $request->input('sms'))){
           $SMSmessage = "(DUZELTME)Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz.";
            $smsSenderFactory->sendSMS($SMSmessage , $employeesa);
        }
            }

           return Response::json(array(
            'messages' => 'Randevu Güncellendi.'
        ));

    }catch(ModelNotFoundException $err){
        return Response::json(array('Bir Hata Oluştu'),500);
    }
        }
    }
    }
    public function pastappointmentsajax(){
         return Datatables::of(Appointment::where('appointment_time','<',new Carbon()))->where('is_cancelled',0)->
         addColumn('customer', function ($appointment) {
             return $appointment->customer->name;
            })->
         addColumn('customerphone', function ($appointment) {
             return $appointment->customer->phone;
            })->
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
    public function checkappointments(Request $request){
        $appointments = Appointment::all();
        $bugun = Carbon::now();
        $txt = '';
        $txt2 = '<div class="uyarilar"><h4>YAKLAŞAN RANDEVULAR</h4>';
         foreach ($appointments as $appo) {
            if($appo->appointment_type == 'planned'){
            if($bugun > $appo->appointment_time){
                $ads = new Carbon($appo->next_appointment_time);
                $appo->appointment_time = $appo->next_appointment_time;
                $appo->next_appointment_time = $ads->copy()->addDays($appo->appointment_range);
                $appo->save();

            }
        }
        $dt = new Carbon($appo->appointment_time);
            if ($bugun->diffInDays($dt) < 7 and $bugun < $appo->appointment_time) {
                $txt .= $appo->customer->name.'/ '.$appo->service->name.' randevusu '.$dt->diffForHumans($bugun).'<br>';
            }
            
        
        }
        if($txt != ''){
            $txt = $txt2.$txt;
            $txt .= '</div>';
        }
        return Response::json(array(
            'messages' => $txt
        ));

    }

    public function profitsAjax(Request $request){
        
        
        if ($request->has('date')) {
            $expdate = explode('/', $request->input('date'));
            $mindate = new Carbon($expdate[0]);
            $maxdate = new Carbon($expdate[1]);
            $appointments = Appointment::whereBetween('appointment_time',[$mindate,$maxdate])->where('is_cancelled',0)->get();
                  
        }else{
            $appointments = Appointment::where('is_cancelled',0)->get();

        }
        $total = 0;
        foreach ($appointments as $appointment) {
           $total += $appointment->service_pay - ($appointment->employee_pay * $appointment->employees()->count());
        }
        return Datatables::of($appointments)->
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
            })->
            addColumn('action', function ($appointment) {
                $profit = $appointment->service_pay - ($appointment->employee_pay * $appointment->employees()->count());
                return $profit;
            })->with('sumprofit',$total)->
            make(true);
    }

    public function appointmentDestroy(Request $request,$id){
            $appointment = Appointment::find($id);
            $appointment->delete();
    }
    public function appointmentCancel(Request $request,$id){
        try{
            $appointment = Appointment::findOrFail($id);
            $appointment->is_cancelled = 1;
            $appointment->save();
            $appointment_time = new Carbon($appointment->appointment_time);
             $SMSmessage = "Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihli randevunuz iptal edilmiştir. 0 545 452 50 83 – 0 232 502 44 42 numaralı telefonu arayıp bilgi alabilirsiniz";
             $phones = array();
             $srcphone = array('(',')',' ','-');
            $rpcphone = array('','','','');
            array_push($phones, str_replace($srcphone, $rpcphone,$appointment->customer->phone));
            array_push($phones, str_replace($srcphone, $rpcphone,$appointment->driver->phone));
            foreach ($appointment->employees->all() as $employee) {
            array_push($phones, str_replace($srcphone, $rpcphone, $employee->phone));
            }
            $smsSenderFactory = new smsSenderFactory();
            $smsSenderFactory->sendSMS($SMSmessage , $phones);
            return Response::json(array('messages' => 'Randevu İptal Edildi'));
        }catch(ModelNotFoundException $err){
        }
    }




    public function plannedappointmentasignEmployee($id){
         try{
        $appointment = Appointment::findOrFail($id);
        $employees = Employee::where('confirmed',1)->get();
        return view('appointments.asignemployee',['id' => $id,'appointment' => $appointment,'employees' => $employees]);
        
    }catch(ModelNotFoundException $err){
        return redirect()->back()->withErrors('Hata Oluştu.');
    }
    }
    public function plannedappointmentasignEmployeePost(Request $request,$id){
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
        $appointment = Appointment::findOrFail($id);
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
            $employeenametext = '';
            foreach ($request->input('employee_id') as $value) {
                $employee = Employee::find($value);
                $appointment->employees()->attach($employee->id);
                array_push($employeesa, str_replace($srcphone, $rpcphone, $employee->phone));
                $employeenametext .= $employee->name.", ";
            }
            $employeenametext = rtrim($employeenametext,',');
            $googlemapslink = '';
             if($appointment->customer->address_coordinates != null){
                    $srcmap = array('(',')',' ');
                $rpcmap = array('','','');
                $googlemapslink .= "Konum Linki : http://www.google.com/maps/place/".str_replace($srcmap, $rpcmap,$appointment->customer->address_coordinates);
                }
            $smsSenderFactory = new smsSenderFactory();
             $employeeMessage = "Merhaba ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')." tarihinde ".$servicestext." için çağırılmaktasınız. Lütfen belirtilen saatten 1 saat öncesinde hazır olup, şoförün sizi aramasını bekleyiniz.";
            $smsSenderFactory->sendSMS($employeeMessage , $employeesa);

            $drivera = array();
            array_push($drivera, str_replace($srcphone, $rpcphone,$appointment->driver->phone));
            
            $SMSmessage = "Merhaba ".$appointment->driver->name." ; ".$appointment_time->formatLocalized('%A %d %B %Y %H:%M')."‘da ".$appointment->customer->address." adresinde ".$servicestext." için, ".$employeenametext." personeller bırakılacaktır. Müşteriden tahsil edilecek tutar : ".$appointment->service_pay." TL’dir. Ödeme Türü : ".$appointment->payment_type." ".$appointment->customer->name." : Müşteri yol tarifi : ".$appointment->customer->address_direction." ".$googlemapslink;
            $smsSenderFactory->sendSMS($SMSmessage , $drivera);
            return Response::json(array(
                'messages' => 'Personel Ataması Yapıldı.'
            ),200);
        }

}
    }
}

