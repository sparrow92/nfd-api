<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Core\BaseFormRequest;

class CompanyRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'name' => ['required', 'max:255'],
        'nip' => ['required', 'digits_between:10,11', 'unique:companies,nip'],
        'address' => ['required', 'max:255'],
        'city' => ['required', 'max:255'],
        'postcode' => ['required', 'regex:/^\d{2}-\d{3}$/'],
      ];
    }
}
