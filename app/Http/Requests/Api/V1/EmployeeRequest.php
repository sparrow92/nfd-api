<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Core\BaseFormRequest;

class EmployeeRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'first_name' => ['required', 'max:255', 'alpha'],
        'last_name' => ['required', 'max:255', 'alpha'],
        'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
        'phone_number' => ['nullable', 'unique:employees,phone_number', 'regex:/^\+?[0-9\s\-\(\)]{9,15}$/'],
      ];
    }
}
