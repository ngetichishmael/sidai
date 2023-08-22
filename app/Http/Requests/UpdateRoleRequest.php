<?php

namespace App\Http\Requests;

use App\Role;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
//        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'name'         => [
                'required',
            ]
        ];
    }
}
