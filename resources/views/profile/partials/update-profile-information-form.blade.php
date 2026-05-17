<section class="text-white border border-white p-4 rounded-md">
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white"/>
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-transparent text-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <p class="text-xs text-white-500 mt-1">Note: You must provide either an email or contact number for appointment confirmations</p>
            <x-input-label for="email" :value="__('Email')" class="mt-4 text-white"/>
            <div class="relative mt-1">
                <x-text-input 
                    id="email" 
                    name="email" 
                    type="email" 
                    class="block w-full pr-10 bg-transparent text-white placeholder-gray-400" 
                    :value="old('email', $user->email)" 
                    autocomplete="username" 
                    placeholder="Enter email here (juandelacruz@gmail.com)"
                />
            
                @if ($user->email && $user->hasVerifiedEmail())
                    <span class="absolute right-0 top-1/2 transform -translate-y-1/2 mr-2 text-green-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-white">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="contact_number" :value="__('Contact Number')" class="text-white" />
            
            <div class="relative mt-1">
                <div class="flex">
                    <!-- Country code -->
                    <span class="px-3 py-2 border border-gray-400 border-r-0 rounded-l-md text-white text-sm">+63</span>
            
                    <!-- Input field -->
                    <x-text-input 
                        id="contact_number" 
                        name="contact_number" 
                        type="text" 
                        class="flex-1 pr-10 bg-transparent text-white placeholder-gray-400 rounded-r-md" 
                        placeholder="Enter contact number here (9123456789)" 
                        :value="old('contact_number', $user->contact_number ? substr($user->contact_number, 2) : '')"
                    />
                </div>
            
                <!-- Verification check mark -->
                @if ($user->contact_number && $user->phone_verified_at)
                    <span class="absolute right-0 top-1/2 transform -translate-y-1/2 mr-2 text-green-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-white-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
