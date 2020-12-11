@if (!$model->isAdmin())
    <x-utils.edit-button :href="route('admin.auth.role.edit', $model)" />

    @if ($isDiffDynamicApiGuest)
        <x-utils.delete-button :href="route('admin.auth.role.destroy', $model)" />
    @endif

@endif
