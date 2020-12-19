<footer class="c-footer">
    <div>
        <strong>
            @lang('Copyright') &copy; {{ date('Y') }}
            <x-utils.link href="https://www.marcoslima.com/products/safe-dynamic-api" target="_blank" :text="__(appName())" />
        </strong>

        @lang('All Rights Reserved')
    </div>

    <div class="mfs-auto">
        @lang('Powered by')
        <x-utils.link href="https://www.marcoslima.com/products/safe-dynamic-api" target="_blank" :text="__(appName())" />
    </div>
</footer>
