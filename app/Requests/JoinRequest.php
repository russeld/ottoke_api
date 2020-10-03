<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinRequest extends FormRequest
{
    public function authorize()
    {
      return true;
    }

    public function rules()
    {
         return [
            'username' => 'required|min:8|max:50|string|unique:users,username',
            'password' => 'required|min:8|max:50|string|alpha_num',
            'uuid' => 'required|exists:clients,uuid'
        ];
    }
}
