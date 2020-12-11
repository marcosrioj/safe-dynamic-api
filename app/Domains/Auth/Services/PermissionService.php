<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Models\Permission;
use App\Services\BaseService;
use Exception;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionService.
 */
class PermissionService extends BaseService
{
    /**
     * PermissionService constructor.
     *
     * @param  Permission  $permission
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

    /**
     * @return mixed
     */
    public function getCategorizedPermissions()
    {
        return $this->model::isMaster()
            ->with('children')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getUncategorizedPermissions()
    {
        return $this->model::singular()
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return Permission
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = []): Permission
    {
        DB::beginTransaction();

        try {
            $permission = $this->model::create([
                'type' => $data['type'],
                'name' => $data['name'],
                'description' => $data['description'],
                'parent_id' => $data['parent_id'],
                'sort' => is_null($data['sort']) ? 1 : $data['sort']
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the permission.'));
        }

        DB::commit();

        return $permission;
    }

    /**
     * @param  Permission  $permission
     * @param  array  $data
     *
     * @return Permission
     * @throws GeneralException
     * @throws \Throwable
     */
    public function update(Permission $permission, array $data = []): Permission
    {
        DB::beginTransaction();
        
        try {
            $permission->update([
                'type' => $data['type'],
                'name' => $data['name'],
                'description' => $data['description'],
                'parent_id' => $data['parent_id'],
                'sort' => is_null($data['sort']) ? 1 : $data['sort']
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating the permission.'));
        }

        DB::commit();

        return $permission;
    }

    /**
     * @param  Permission  $permission
     *
     * @return bool
     * @throws GeneralException
     */
    public function destroy(Permission $permission): bool
    {
        if ($permission->users()->count()) {
            throw new GeneralException(__('You can not delete a permission with associated users.'));
        }

        if ($this->deleteById($permission->id)) {
            return true;
        }

        throw new GeneralException(__('There was a problem deleting the permission.'));
    }
}
