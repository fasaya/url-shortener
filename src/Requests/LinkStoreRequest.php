<?php

namespace Fasaya\UrlShortener\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LinkStoreRequest extends FormRequest
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
            'redirect_to'  => 'required|url:http,https',
            'is_custom_checkbox'  => 'required',
            'have_expiration_date_checkbox'  => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->sometimes('custom', 'required|regex:/^(?!.*\s)[A-Za-z0-9_.-]+$/', function () {
            return request()->is_custom_checkbox == 'on';
        })->sometimes('expiration_date', 'required', function () {
            return request()->have_expiration_date_checkbox == 'on';
        });
    }
}
