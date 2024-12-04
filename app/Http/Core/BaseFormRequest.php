<?php

namespace App\Http\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseFormRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json([
      'message' => __('validation.errors'),
      'errors' => $validator->errors(),
    ], 422));
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'name.required' => __('validation.name.required'),
      'name.max' => __('validation.name.max'),
      'nip.required' => __('validation.nip.required'),
      'nip.digits_between' => __('validation.nip.digits_between'),
      'nip.unique' => __('validation.nip.unique'),
      'address.required' => __('validation.address.required'),
      'address.max' => __('validation.address.max'),
      'city.required' => __('validation.city.required'),
      'city.max' => __('validation.city.max'),
      'postcode.required' => __('validation.postcode.required'),
      'postcode.regex' => __('validation.postcode.regex'),
      'first_name.required' => __('validation.first_name.required'),
      'first_name.alpha' => __('validation.first_name.alpha'),
      'first_name.max' => __('validation.first_name.max'),
      'last_name.required' => __('validation.last_name.required'),
      'last_name.alpha' => __('validation.last_name.alpha'),
      'last_name.max' => __('validation.last_name.max'),
      'email.required' => __('validation.email.required'),
      'email.email' => __('validation.email.email'),
      'email.max' => __('validation.email.max'),
      'email.unique' => __('validation.email.unique'),
      'phone_number.regex' => __('validation.phone_number.regex'),
      'phone_number.unique' => __('validation.phone_number.unique'),
      'company_id.required' => __('validation.company_id.required'),
      'company_id.exists' => __('validation.company_id.exists'),
    ];
  }
}
