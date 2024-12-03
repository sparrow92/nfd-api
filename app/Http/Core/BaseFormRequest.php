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
      'message' => 'Wystąpiły błędy walidacji.',
      'errors' => $validator->errors(), // Zwrócenie wszystkich błędów
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
      'name.required' => 'Nazwa jest wymagana.',
      'name.max' => 'Nazwa nie może mieć więcej niż 255 znaków.',
      'nip.required' => 'NIP jest wymagany.',
      'nip.digits_between' => 'NIP musi mieć od 10 do 11 cyfr.',
      'nip.unique' => 'NIP musi być unikalny.',
      'address.required' => 'Adres jest wymagany.',
      'address.max' => 'Adres nie może mieć więcej niż 255 znaków.',
      'city.required' => 'Miasto jest wymagane.',
      'city.max' => 'Miasto nie może mieć więcej niż 255 znaków.',
      'postcode.required' => 'Kod pocztowy jest wymagany.',
      'postcode.regex' => 'Kod pocztowy musi mieć format XX-XXX.',
      'first_name.required' => 'Imię jest wymagane.',
      'first_name.alpha' => 'Imię może zawierać tylko litery.',
      'first_name.max' => 'Imię nie może mieć więcej niż 255 znaków.',
      'last_name.required' => 'Nazwisko jest wymagane.',
      'last_name.alpha' => 'Nazwisko może zawierać tylko litery.',
      'last_name.max' => 'Nazwisko nie może mieć więcej niż 255 znaków.',
      'email.required' => 'Adres e-mail jest wymagany.',
      'email.email' => 'Podaj poprawny adres e-mail.',
      'email.max' => 'Adres e-mail nie może mieć więcej niż 255 znaków.',
      'email.unique' => 'Adres e-mail musi być unikalny.',
      'phone_number.regex' => 'Numer telefonu musi mieć poprawny format.',
      'phone_number.unique' => 'Numer telefonu musi być unikalny.',
      'company_id.required' => 'ID firmy jest wymagane.',
      'company_id.exists' => 'Firma o podanym ID nie istnieje.',
    ];
  }
}
