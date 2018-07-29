<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'phone' => 'required|min:10|string'
        
        ];
    }
    public function messages(){

        return [
        'name.required' => 'Personel İsmi Boş Bırakılamaz',
        'phone.required'  => 'Personel Telefon Numarası Boş Bırakılamaz'
    ];
    }
}
