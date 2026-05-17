<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, please verify your phone number by sending an OTP to it.') }}
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 w-full max-w-sm mx-auto">

        <!-- Verify OTP -->
        <form method="POST" action="{{ route('phone.verifyOtp') }}">
            @csrf
            <div class="mb-3">
                <label for="otp" class="sr-only">OTP</label>
                <input id="otp" name="otp" type="text" placeholder="Enter OTP"
                       class="w-full border px-3 py-2 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit"
                    class="w-full border border-gray-400 bg-[#E8B931] text-black font-medium px-4 py-2 rounded-md hover:bg-yellow-500 transition">
                {{ __('Confirm OTP') }}
            </button>
        </form>

        <!-- Send OTP -->
        <form method="POST" action="{{ route('phone.sendOtp') }}">
            @csrf
            <button type="submit"
                    class="w-full border border-gray-400 text-gray-700 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                {{ __('Request OTP') }}
            </button>
        </form>

        <!-- Log Out -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full text-gray-500 hover:text-gray-800 text-sm underline mt-2">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
