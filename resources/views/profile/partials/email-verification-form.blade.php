@if(!auth()->user()->hasVerifiedEmail() && !empty(auth()->user()->email))
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
            Verify Email
        </button>
    </form>
@endif
