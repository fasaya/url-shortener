<?php

namespace Fasaya\UrlShortener\Requests;

use Fasaya\UrlShortener\Exceptions\ValidationException;
use Fasaya\UrlShortener\UrlShortener;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LinkUpdateRequest extends FormRequest
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
            'have_expiration_date_checkbox'  => 'nullable|in:on',
            'is_disabled'  => 'in:1,0',
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
        $validator->sometimes('expiration_date', 'required', function () {
            return request()->have_expiration_date_checkbox === 'on';
        });
    }
}
