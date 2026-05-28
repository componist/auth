<form {{ $attributes->merge(['x-data' => ''])->class(['auth-form']) }}>
    {{ $slot }}
</form>
