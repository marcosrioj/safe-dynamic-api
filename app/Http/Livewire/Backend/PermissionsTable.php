<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: PermissionsTable.php
 */
namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\DynamicApiGuest;
use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class PermissionsTable.
 */
class PermissionsTable extends TableComponent
{
    use HtmlComponents;

    /**
     * @var string
     */
    public $sortField = 'id';

    /**
     * @var string
     */
    public $sortDirection = 'desc';

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped',
    ];

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return Permission::with('permissions:id,name,description')
            ->withCount('users');
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Id'), 'id')
                ->searchable()
                ->sortable(),
            Column::make(__('Type'), 'type')
                ->sortable()
                ->format(function (Permission $model) {
                    if ($model->type === User::TYPE_ADMIN) {
                        return __('Administrator');
                    }

                    if ($model->type === User::TYPE_USER) {
                        return __('User');
                    }

                    if ($model->type === DynamicApiGuest::ROLE_TYPE) {
                        return DynamicApiGuest::ROLE_TYPE;
                    }

                    return 'N/A';
                }),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Description'), 'description')
                ->searchable()
                ->sortable(),
            Column::make(__('Sort'), 'sort')
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Permission $model) {
                    return view('backend.auth.permission.includes.actions', ['model' => $model]);
                }),
        ];
    }
}
