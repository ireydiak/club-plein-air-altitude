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
            'firstName'     => 'required|regex:/^([0-9\p{Latin}]+[\ -]?)+[a-zA-Z0-9]+$/u',
            'lastName'      => 'required|regex:/^([0-9\p{Latin}]+[\ -]?)+[a-zA-Z0-9]+$/u',
            'email'         => 'required|email|unique:member',
            'role'          => 'required|in:Membre,Admin,Permanent',
            'phone'         => 'nullable|phone:AUTO,CA',
            'phoneRegion'   => 'nullable|required_with:phone',
            'cip'           => 'nullable|regex:/[a-z]{4}[0-9]{4}/i|unique:member_university',
            'facebookLink'  => 'nullable|unique:member_facebook,facebook_link',
        ];
    }
}
