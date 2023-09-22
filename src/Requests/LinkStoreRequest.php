<?php

namespace Fasaya\UrlShortener\Requests;

use Fasaya\UrlShortener\Exceptions\ValidationException;
use Fasaya\UrlShortener\UrlShortener;
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
            'is_custom_checkbox'  => 'nullable|in:on',
            'have_expiration_date_checkbox'  => 'nullable|in:on',
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
            return request()->is_custom_checkbox === 'on';
        })->sometimes('expiration_date', 'required', function () {
            return request()->have_expiration_date_checkbox === 'on';
        });

        $validator->after(function ($validator) {
            if ($this->input('is_custom_checkbox') === 'on') {;
                if (UrlShortener::exists($this->input('custom'))) {
                    $validator->errors()->add('custom', 'The URL already exists and enabled.');
                }
            }
        });
    }
}
