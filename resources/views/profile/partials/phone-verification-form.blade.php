@if ($user->contact_number && ! $user->hasVerifiedPhone())
    <div class="mt-4">
        <p class="text-sm text-white">
            {{ __('Your phone number is unverified.') }}
        </p>

        <form method="post" action="{{ route('phone.sendOtp') }}">
            @csrf
            <x-primary-button>
                {{ __('Send OTP') }}
            </x-primary-button>
        </form>

        @if (session('otp_sent'))
            <form method="post" action="{{ route('phone.verifyOtp') }}" class="mt-2">
                @csrf
                <x-text-input name="otp" placeholder="Enter OTP" class="bg-transparent text-white"/>
                <x-primary-button class="ml-2">
                    {{ __('Verify OTP') }}
                </x-primary-button>
            </form>
        @endif
    </div>
@endif
