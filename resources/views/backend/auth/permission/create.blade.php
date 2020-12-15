@inject('model', '\App\Domains\Auth\Models\User')
@inject('modelDynamicApiGuest', '\App\Domains\Auth\Models\DynamicApiGuest')

@extends('backend.layouts.app')

@section('title', __('Create Permission'))

@section('content')
    <x-forms.post :action="route('admin.auth.permission.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Permission')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.permission.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $model::TYPE_USER }}'}">
                    <div class="form-group row">
                        <label for="type" class="col-md-2 col-form-label">@lang('Type')</label>

                        <div class="col-md-10">
                            <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                                <option value="{{ $modelDynamicApiGuest::ROLE_TYPE }}">@lang('Dynamic Api Guest')</option>
                                <option value="{{ $model::TYPE_USER }}">@lang('User')</option>
                                <option value="{{ $model::TYPE_ADMIN }}">@lang('Administrator')</option>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Examples') }}: get.myTable or post.myTable or admin.access.sample or user.access.sample " value="{{ old('name') }}" maxlength="250" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>

                        <div class="col-md-10">
                            <input type="text" name="description" class="form-control" placeholder="{{ __('Description') }}" value="{{ old('description') }}" maxlength="250" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sort" class="col-md-2 col-form-label">@lang('Sort')</label>

                        <div class="col-md-10">
                            <input type="number" name="sort" class="form-control" placeholder="{{ __('Sort') }}" value="1" maxlength="10" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="parent_id" class="col-md-2 col-form-label">@lang('Parent Id')</label>

                        <div class="col-md-10">
                            <input type="number"  name="parent_id" class="form-control" placeholder="{{ __('Parent Id') }}" value="{{ old('parent_id') }}" maxlength="20" />
                        </div>
                    </div><!--form-group-->
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Permission')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
