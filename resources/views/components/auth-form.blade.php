<form {{ $attributes->merge(['x-data' => ''])->class(['flex flex-col gap-4']) }}>
    {{ $slot }}
</form>
