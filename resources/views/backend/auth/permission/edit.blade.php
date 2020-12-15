@inject('model', '\App\Domains\Auth\Models\User')
@inject('modelDynamicApiGuest', '\App\Domains\Auth\Models\DynamicApiGuest')

@extends('backend.layouts.app')

@section('title', __('Update Permission'))

@section('content')
    <x-forms.patch :action="route('admin.auth.permission.update', $permission)">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Permission')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.permission.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $permission->type }}'}">
                    <div class="form-group row">
                        <label for="id" class="col-md-2 col-form-label">@lang('Id')</label>

                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ $permission->id }}" readonly />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                        <div class="col-md-10">
                            <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                                <option value="{{ $modelDynamicApiGuest::ROLE_TYPE }}" {{ $permission->type === $modelDynamicApiGuest::ROLE_TYPE ? 'selected' : '' }}>@lang('Guest')</option>
                                <option value="{{ $model::TYPE_USER }}" {{ $permission->type === $model::TYPE_USER ? 'selected' : '' }}>@lang('User')</option>
                                <option value="{{ $model::TYPE_ADMIN }}" {{ $permission->type === $model::TYPE_ADMIN ? 'selected' : '' }}>@lang('Administrator')</option>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text"  name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $permission->name }}" maxlength="250" required />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>

                        <div class="col-md-10">
                            <input type="text"  name="description" class="form-control" placeholder="{{ __('Description') }}" value="{{ old('description') ?? $permission->description }}" maxlength="250" required />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="sort" class="col-md-2 col-form-label">@lang('Sort')</label>

                        <div class="col-md-10">
                            <input type="number"  name="sort" class="form-control" placeholder="{{ __('Sort') }}" value="{{ old('sort') ?? $permission->sort }}" maxlength="10" required />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="parent_id" class="col-md-2 col-form-label">@lang('Parent Id')</label>

                        <div class="col-md-10">
                            <input type="number"  name="parent_id" class="form-control" placeholder="{{ __('Parent Id') }}" value="{{ old('parent_id') ?? $permission->parent_id }}" maxlength="20" />
                        </div>
                    </div><!--form-group-->
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Permission')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
