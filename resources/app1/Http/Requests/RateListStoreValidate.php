<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class RateListStoreValidate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pro_id' => 'required',
            'voucher_date'=>'required|date:Y-m-d',
            'voucher_no'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'voucher_no.required' => 'The Document no field is required.'
        ];
    }

    public function redirect()
    {
        return redirect()->back()->with('error_message', 'Something went wrong');
    }
}
