<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect("/login");
});

Route::get('login', 'Auth\AuthController@getLogin')->name('auth.login.get');
Route::post('login', 'Auth\AuthController@postLogin')->name('auth.login.post');
Route::get('logout', 'Auth\AuthController@logout')->name('auth.logout.get');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'MainController@index')->name('home');

    // Customers

	Route::get('/musteriler',function(){
		return view('customers.customersPage',['title' => 'Anasayfa']);
	})->name('customersPage');

	Route::get('/musteriler-ajax','MainController@customersajax')->name('customersajax');

	Route::get('/musteri-ayrintilari/{id}','MainController@customerdetail')->name('customerdetail');

	Route::get('/musteri-ajax/{id}','MainController@customerajax')->name('customerajax');

	Route::post('/musteri-duzenle/{id}','MainController@customerEdit')->name('customerEdit');

	Route::get('/musteri-ekle', function () {
    return view('customers.addcustomer');
	})->name('customeraddg');

	Route::post('/musteri-ekle','MainController@customerAdd')->name('customeradd');

	Route::delete('/musteri-sil/{id}','MainController@customerDestroy')->name('customerDestroy');

	Route::get('/personel-ata/{id}','MainController@addEmployeetoCustomer')->name('addEmployeetoCustomer');

	Route::post('/personel-ata/{id}','MainController@addEmployeetoCustomerPost')->name('addEmployeetoCustomerPost');

	// End of Customers

	// Appointments

	Route::get('/yeni-randevu','AppointmentController@newappointmentPage')->name('newappointmentPage');

	Route::post('/yeni-randevu','AppointmentController@newappointmentPost')->name('newappointmentPost');

	Route::get('/yeni-planli-randevu','AppointmentController@newplannedappointmentPage')->name('newplannedappointmentPage');

	Route::post('/yeni-planli-randevu','AppointmentController@newplannedappointmentPost')->name('newplannedappointmentPost');

	Route::get('/randevular',function(){
		return view('appointments.appointmentsPage');
	})->name('appointmentsPage');

	Route::get('/randevular-ajax','AppointmentController@appointmentsajax')->name('appointmentsajax');

	Route::get('/randevu-duzenle/{id}','AppointmentController@appointmentPage')->name('appointmentPage');

	Route::post('/randevu-duzenle/{id}','AppointmentController@appointmentEdit')->name('appointmentEdit');

	Route::delete('/randevu-sil/{id}','AppointmentController@appointmentDestroy')->name('appointmentDestroy');

	Route::post('/randevu-iptal/{id}','AppointmentController@appointmentCancel')->name('appointmentCancel');

	Route::get('/iptal-randevular',function(){
		return view('appointments.cancelledappointmentsPage');
	})->name('cancelledappointments');

	Route::get('/iptal-randevular-ajax','AppointmentController@cancelledappointmentsAjax')->name('cancelledappointmentsAjax');

	Route::get('/gecmis-randevular',function(){
		return view('appointments.pastappointmentsPage');
	})->name('pastappointmentsPage');

	Route::get('/deneme','AppointmentController@denemem');
	
	Route::get('/gecmis-randevular-ajax','AppointmentController@pastappointmentsajax')->name('pastappointmentsajax');

	Route::get('/planli-randevular',function(){
		return view('appointments.plannedappointmentsPage');
	})->name('plannedappointmentsPage');

	Route::get('/planli-randevular-ajax','AppointmentController@plannedappointmentsajax')->name('plannedappointmentsajax');

	Route::get('/planli-randevu-duzenle/{id}','AppointmentController@plannedappointmentPage')->name('plannedappointmentPage');

	Route::post('/planli-randevu-duzenle/{id}','AppointmentController@plannedappointmentEdit')->name('plannedappointmentEdit');

	Route::get('/planli-randevu-personel-ata/{id}','AppointmentController@plannedappointmentasignEmployee')->name('plannedappointmentasignEmployee');

	Route::post('/planli-randevu-personel-ata/{id}','AppointmentController@plannedappointmentasignEmployeePost')->name('plannedappointmentasignEmployeePost');

	Route::post('/checkappointments','AppointmentController@checkappointments')->name('checkappointments');

	Route::get('/kazanclar',function(){
		return view('appointments.profits');
	})->name('profitsPage');

	Route::get('/kazanclar-ajax','AppointmentController@profitsAjax')->name('profitsAjax');
	
	// End of Appointments

	// Employees
	
	Route::get('/personel-ekle',function(){
		return view('employees.newemployee');
	})->name('newemployeePage');

	Route::post('/personel-ekle','EmployeeController@newemployeePost')->name('newemployeePost');

	Route::get('/sofor-ekle',function(){
		return view('employees.newdriver');
	})->name('newdriverPage');

	Route::post('/sofor-ekle','DriverController@newdriverPost')->name('newdriverPost');

	Route::get('personeller',function(){
		return view('employees.employeesPage');
	})->name('employeesPage');
	
	Route::get('/personeller-ajax','EmployeeController@employeesajax')->name('employeesajax');

	Route::get('/personel-ayrintilari/{id}','EmployeeController@employeedetail')->name('employeedetail');

	Route::get('/personel-ajax/{id}','EmployeeController@employeeajax')->name('employeeajax');

	Route::post('/personel-duzenle/{id}','EmployeeController@employeeEdit')->name('employeeEdit');

	Route::delete('/personel-sil/{id}','EmployeeController@employeeDestroy')->name('employeeDestroy');

	Route::get('/personel-odemeleri','EmployeeController@employeesPayPage')->name('employeesPayPage');

	Route::get('/personel-odemeleri-ajax','EmployeeController@employeesPayAjax')->name('employeesPayAjax');

	Route::get('/personel-odeme-detaylari/{id}','EmployeeController@profitDetails')->name('profitDetails');

	Route::get('/personel-odeme-detaylari-ajax/{id}','EmployeeController@employeeprofitAjax')->name('employeeprofitAjax');
	Route::post('/personel-odeme-yap/{id}','EmployeeController@paytoEmployee')->name('paytoEmployee');

	Route::get('/personel-gecmis-odemeler/{id}',function($id){
		return view('employees.pastProfitforEmployeePage',['id' => $id]);
	})->name('pastProfitforEmployeePage');

	Route::get('/personel-gecmis-odemeler-ajax/{id}','EmployeeController@pastProfitforEmployeeAjax')->name('pastProfitforEmployeeAjax');
	route::get('/soforler',function(){
		return view('employees.driversPage');
	})->name('driversPage');

	Route::get('/soforler-ajax','DriverController@driversajax')->name('driversajax');

	Route::delete('/sofor-sil/{id}','DriverController@driverDestroy')->name('driverDestroy');

	Route::get('/sofor-ayrintilari/{id}','DriverController@driverDetails')->name('driverDetails');

	Route::post('/sofor-duzenle/{id}','DriverController@driverEdit')->name('driverEdit');

	// End of Employees

	// Services

	Route::get('/hizmet-ekle',function(){
		return view('services.newservice');
	})->name('newservicePage');

	Route::post('/hizmet-ekle','ServiceController@newservicePost')->name('newservicePost');

		route::get('/hizmet-gruplari',function(){
		return view('services.servicesPage');
	})->name('servicesPage');

	Route::get('/hizmetler-ajax','ServiceController@servicesajax')->name('servicesajax');	

	Route::delete('/hizmet-sil/{id}','ServiceController@serviceDestroy')->name('serviceDestroy');

	Route::get('/hizmet-duzenle/{id}','ServiceController@serviceDetails')->name('serviceDetails');

	Route::post('/hizmet-duzenle/{id}','ServiceController@serviceEdit')->name('serviceEdit');

	// End of Services

	Route::get('/toplu-sms',function(){
		return view('sendSMS');
	})->name('smsPage');

	Route::post('/toplu-sms','MainController@sendSms')->name('sendSms');

	Route::get('/yetkili-ekle',function(){
		return view('addUser');
	})->name('addUser');

	
	Route::get('/yetkililer',function(){
		return view('users');
	})->name('users');
	
	Route::get('/yetkililer-ajax','MainController@usersAjax')->name('usersAjax');

	Route::post('/yetkili-ekle','MainController@adduserPost')->name('newuserPost');

	Route::get('/yetkili-duzenle/{id}','MainController@edituser')->name('edituser');
	Route::post('/yetkili-duzenle/{id}','MainController@edituserPost')->name('edituserPost');

	Route::get('/memnuniyet-sistemi',function(){
		return view('appointments.satisfactions');
	})->name('satisfactionsPage');

	Route::get('/memnuniyet-sistemi-ajax','AppointmentController@satisfactionsAjax')->name('satisfactionsAjax');

	Route::post('/musteri-ara/{id}','AppointmentController@callCustomer')->name('callCustomer');

	Route::get('/memnuniyet-raporlari',function(){
		return view('appointments.satisfactionReports');
	})->name('satisfactionReports');

	Route::get('/memnuniyet-raporlari-ajax','AppointmentController@satisfactionReportsAjax')->name('satisfactionReportsAjax');

	Route::get('/kampanyalar',function(){
		return view('campaigns.campaignsPage');
	})->name('campaignsPage');

	Route::get('/gecmis-kampanyalar',function(){
		return view('campaigns.pastcampaignsPage');
	})->name('pastcampaignsPage');

});
