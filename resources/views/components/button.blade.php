<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#4dabb4] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4dabb4]/80 focus:bg-[#4dabb4]/80 active:bg-[#4dabb4]/90 focus:outline-none focus:ring-2 focus:ring-[#b9f362] focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
