<?php

namespace App\Domains\Auth\Http\Requests\Backend\Permission;

use App\Domains\Auth\Models\DynamicApiGuest;
use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StorePermissionRequest.
 */
class StorePermissionRequest extends FormRequest
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
            'type' => ['required', Rule::in([User::TYPE_ADMIN, User::TYPE_USER, DynamicApiGuest::ROLE_TYPE]), Rule::unique('permissions')->where('name', $this['name'])],
            'name' => ['required', 'max:250', Rule::unique('permissions')->where('type', $this['type'])],
            'description' => ['required', 'max:250'],
            'sort' => ['max:10'],
            'parent_id' => ['max:20'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
