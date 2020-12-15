<?php

namespace App\Domains\Auth\Http\Requests\Backend\Permission;

use App\Domains\Auth\Models\DynamicApiGuest;
use App\Domains\Auth\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdatePermissionRequest.
 */
class UpdatePermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in([User::TYPE_ADMIN, User::TYPE_USER, DynamicApiGuest::ROLE_TYPE]), Rule::unique('permissions')->where('name', $this['name'])->ignore($this->permission)],
            'name' => ['required', 'max:250', Rule::unique('permissions')->where('type', $this['type'])->ignore($this->permission)],
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

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('You can not edit the main permissions.'));
    }
}
