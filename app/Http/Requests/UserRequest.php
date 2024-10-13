<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'=>'nullable',
            'name' => 'required|string',
            'mobile' => 'required|unique:users,mobile,' . $this->user?->id,
            'password' => [$this->user ? 'nullable' : 'required', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,name',
            'branch_id' => 'required|exists:branches,id',
        ];
    }
}
