<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use PasswordValidationRules;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['string', 'max:255', 'in:USER,ADMIN,TALENT,PARTNER'],
            'phone_number' => ['numeric', 'nullable'],
            'social_media' => ['string', 'max:255', 'nullable'],
            'socmed_detail' => ['string', 'max:255', 'nullable'],
            'total_followers' => ['numeric', 'nullable'],
            'company' => ['string', 'max:255', 'nullable'],
            'web_linkedin' => ['string', 'max:255', 'nullable'],
            'partner_role' => ['string', 'max:255', 'nullable'],
            'industry' => ['string', 'max:255', 'nullable'],
            'npwp' => ['numeric', 'nullable'],
            'city'=> ['string', 'max:255', 'nullable'],
            'description' => ['string', 'max:255', 'nullable'],
            'is_joined' => ['integer','nullable', 'in:0,1'],
            'is_active' => ['integer','required', 'in:0,1'],
        ];
    }
}
