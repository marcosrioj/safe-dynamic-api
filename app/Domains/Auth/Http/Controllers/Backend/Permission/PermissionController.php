<?php

namespace App\Domains\Auth\Http\Controllers\Backend\Permission;

use App\Domains\Auth\Http\Requests\Backend\Permission\DeletePermissionRequest;
use App\Domains\Auth\Http\Requests\Backend\Permission\EditPermissionRequest;
use App\Domains\Auth\Http\Requests\Backend\Permission\StorePermissionRequest;
use App\Domains\Auth\Http\Requests\Backend\Permission\UpdatePermissionRequest;
use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Services\PermissionService;
use App\Http\Controllers\Controller;

/**
 * Class PermissionController.
 */
class PermissionController extends Controller
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * PermissionController constructor.
     *
     * @param  PermissionService  $permissionService
     * @param  PermissionService  $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.auth.permission.index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return view('backend.auth.permission.create');
    }

    /**
     * @param  StorePermissionRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->store($request->validated());

        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('The permission was successfully created.'));
    }

    /**
     * @param  EditPermissionRequest  $request
     * @param  Permission  $permission
     *
     * @return mixed
     */
    public function edit(EditPermissionRequest $request, Permission $permission)
    {
        return view('backend.auth.permission.edit')
            ->withPermission($permission);
    }

    /**
     * @param  UpdatePermissionRequest  $request
     * @param  Permission  $permission
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->permissionService->update($permission, $request->validated());

        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('The permission was successfully updated.'));
    }

    /**
     * @param  DeletePermissionRequest  $request
     * @param  Permission  $permission
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(DeletePermissionRequest $request, Permission $permission)
    {
        $this->permissionService->destroy($permission);

        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('The permission was successfully deleted.'));
    }
}
