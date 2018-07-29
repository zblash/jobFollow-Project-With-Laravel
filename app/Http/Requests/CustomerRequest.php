<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'phone' => 'required|min:10|string|unique:customers',
            'tc_no' => 'required|min:11|string|unique:customers',    
            'address' => 'required|string|max:150',
            'billing' => 'required|string|max:150',
            'customer_level' => 'required'
        ];
    }
    public function messages()
{
    return [
        'name.required' => 'Müşteri İsmi Boş Bırakılamaz',
        'phone.required'  => 'Müşteri Telefon Numarası Boş Bırakılamaz',
        'tc_no.required' => 'Müşteri T.C Kimlik No Boş Bırakılamaz',
        'address.required'  => 'Müşteri Adresi Boş Bırakılamaz',
        'billing.required' => 'Müşteri Fatura Bilgisi Boş Bırakılamaz',
        'customer_level.required'  => 'Müşteri Tipi Boş Bırakılamaz',
    ];
}
}
