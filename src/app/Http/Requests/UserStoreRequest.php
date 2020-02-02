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
            'firstName'     => 'required|alpha',
            'lastName'      => 'required|alpha',
            'password'      => 'required',
            'email'         => 'nullable|email|unique:member_email|required_without_all:cip,facebook',
            'cip'           => 'nullable|regex:/[a-z]{4}[0-9]{4}/i|unique:member_university|required_without_all:email,facebook',
            'facebookLink'  => 'nullable|unique:member_facebook,facebook_link|required_without_all:email,cip',
            'isPermanent'   => 'required|boolean',
            'isAdmin'       => 'required|boolean'
        ];
    }
}
