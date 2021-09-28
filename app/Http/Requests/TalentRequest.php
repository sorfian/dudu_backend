<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TalentRequest extends FormRequest
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
            
                'user_id' => 'required|integer',
                'type' => 'required|max:255|string',
                'category' => 'required|max:255|string',
                'rate' => 'required|numeric|min:0',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'picture_path' => 'required|image',
        
        ];
    }
}
