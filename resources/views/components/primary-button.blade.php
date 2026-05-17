<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-white rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#FEC147] focus:bg-[#FEC147] active:bg-[#FEC147] focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
