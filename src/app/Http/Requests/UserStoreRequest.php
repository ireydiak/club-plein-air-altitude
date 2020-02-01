<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'firstName' => 'required|alpha',
            'lastName' => 'required|alpha',
            'password' => 'required',
            'email' => 'nullable|email',
            'cip' => 'nullable|regex:/[a-z]{4}[0-9]{4}/i',
            'facebook' => 'nullable|regex:/facebook.com/*/i'
        ];
    }
}
