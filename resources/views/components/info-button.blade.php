<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-info px-2 py-0 text-white text-sm']) }}>
    {{ $slot }}
</button>