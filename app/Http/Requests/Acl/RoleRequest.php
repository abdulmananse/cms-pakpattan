<?php

namespace App\Http\Requests\Acl;

use App\Http\Requests\BaseRequest;


class RoleRequest extends BaseRequest
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
            'name' => 'required|max:50|unique:roles,name,'.@$this->role->id,
        ];
    }
}
