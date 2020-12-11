@inject('model', '\App\Domains\Auth\Models\User')
@inject('modelDynamicApiGuest', '\App\Domains\Auth\Models\DynamicApiGuest')

@extends('backend.layouts.app')

@section('title', __('Update Role'))

@section('content')
    <x-forms.patch :action="route('admin.auth.role.update', $role)">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Role')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.role.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $role->type }}'}">
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                        <div class="col-md-10">
                            <select name="type" class="form-control" required x-on:change="userType = $event.target.value" {{ $role->type === $modelDynamicApiGuest::ROLE_TYPE ? 'disabled' : '' }}>
                                <option value="{{ $model::TYPE_USER }}" {{ $role->type === $model::TYPE_USER ? 'selected' : '' }}>@lang('User')</option>
                                <option value="{{ $model::TYPE_ADMIN }}" {{ $role->type === $model::TYPE_ADMIN ? 'selected' : '' }}>@lang('Administrator')</option>
                                <option value="{{ $modelDynamicApiGuest::ROLE_TYPE }}" {{ $role->type === $modelDynamicApiGuest::ROLE_TYPE ? 'selected' : '' }}>@lang('Dynamic Api Guest')</option>
                            </select>

                            @if($role->type === $modelDynamicApiGuest::ROLE_TYPE)
                               <input type="hidden" name="type" value="{{$role->type}}" />
                            @endif
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text"  name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $role->name }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->

                    @include('backend.auth.includes.permissions')
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Role')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
