<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'name' => 'required|max:150|string',
            'phone' => 'required|min:10|string',
            'tc_no' => 'min:11|string',    
            'bank' => 'string|max:150',
            'bankaccountowner' => 'string|max:150',
            'iban' => 'string|max:150',
            'confirmed' => 'required',
            'tc_pic' => 'image|mimes:jpg,png'
        
        ];
    }

    public function messages(){

        return [
        'name.required' => 'Personel İsmi Boş Bırakılamaz',
        'phone.required'  => 'Personel Telefon Numarası Boş Bırakılamaz',
        'tc_no.required' => 'Personel T.C Kimlik No Boş Bırakılamaz',
        'bank.required'  => 'Personel Bankası Bırakılamaz',
        'bankaccountowner.required' => 'Personel Banka Hesabı Boş Bırakılamaz',
        'iban.required'  => 'Personel IBAN Boş Bırakılamaz',
        'confirmed.required'  => 'Personel Durumu Boş Bırakılamaz',
        'tc_pic.required'  => 'Personel Kimlik Fotokopisi Boş Bırakılamaz'
    ];
    }
}
