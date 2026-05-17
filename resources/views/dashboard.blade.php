<x-app-layout title="">
    @section('title', 'User Dashboard')
    @vite('resources/css/dashboard.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(isset($activeAppointment))
        <div class="container">
            <h2 class = "app-details">Your current appointment</h2>
            <div class="message">
                <div class="appointment-details">
                    <ul>
                        <li class="app-details"><strong>Date:</strong> {{ \Carbon\Carbon::parse($activeAppointment->appointment_date)->format('F j, Y') }}</li>
                        <li class = "app-details"><strong>Time:</strong> {{ $activeAppointment->appointment_time }}</li>
                        <li class = "app-details"><strong>Branch:</strong> {{ $activeAppointment->branch->name }} - {{ $activeAppointment->branch->address }}</li>
                        <li class = "app-details"><strong>Barber:</strong> {{ $activeAppointment->barber->name }}</li>
                    </ul>
                </div>
                <div class="button-group">
                    <a href="{{ route('appointment.location') }}" class="btn btn-primary">RESCHEDULE APPOINTMENT</a>
                    <form id="cancelForm" action="{{ route('appointment.cancel', $activeAppointment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="cancel_reason" id="cancel_reason_input">
                        <button type="button" class="btn btn-cancel" onclick="confirmCancel()">
                            CANCEL APPOINTMENT
                        </button>
                    </form>
                </div>
            </div>
            <button class="btn btn-view mt-4" onclick="showLoyaltyCard()">VIEW LOYALTY CARD</button>
        </div>
        @elseif(isset($pendingAppointment))
        <div class="container">
            <h2 class="app-details">You have a pending appointment</h2>
            <p class="subtitle">Please confirm your appointment by typing the OTP sent via email or SMS</p>
        
            @if(session('error'))
                <div class="error-box">
                    {{ session('error') }}
                </div>
            @endif
        
            @php
                $canResend = !$pendingAppointment->otp_sent_at ||
                    \Carbon\Carbon::parse($pendingAppointment->otp_sent_at)->addMinutes(5)->isPast();
            @endphp
        
            <div class="pending-wrapper">
        
                <!-- LEFT: OTP BOX -->
                <div class="otp-box">
        
                    <form method="POST" action="{{ route('appointment.verify.otp', $pendingAppointment->id) }}">
                        @csrf
                        <input type="text" name="otp" placeholder="Enter Confirmation OTP" required class="otp-input">
                        <button type="submit" class="btn btn-primary">Submit OTP</button>
                    </form>
        
                    @if($canResend)
                        <form method="POST" action="{{ route('appointment.send.otp', $pendingAppointment->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Resend OTP
                            </button>
                        </form>
                    @else
                        <p class="limit-otp">
                            You can resend OTP again after 5 minutes.
                        </p>
                    @endif
        
                </div>
        
                <!-- RIGHT: DETAILS BOX -->
                <div class="details-box">
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($pendingAppointment->appointment_date)->format('F j, Y') }}</p>
                    <p><strong>Time:</strong> {{ $pendingAppointment->appointment_time }}</p>
                    <p><strong>Branch:</strong> {{ $pendingAppointment->branch->name }}</p>
                    <p><strong>Barber:</strong> {{ $pendingAppointment->barber->name }}</p>
                </div>
        
            </div>
        
            <!-- BOTTOM BUTTONS -->
            <div class="button-group">
                <form id="cancelForm" action="{{ route('appointment.cancel', $pendingAppointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="cancel_reason" id="cancel_reason_input">
                    <button type="button" class="btn btn-cancel" onclick="confirmCancel()">
                        CANCEL PENDING APPOINTMENT
                    </button>
                </form>
        
                <button class="btn btn-view" onclick="showLoyaltyCard()">VIEW LOYALTY CARD</button>
            </div>
        </div>
        @else
        <div class="container">
            <p>You don't have any active appointments</p>
        
            <div class="button-group">
                <a href="{{ route('appointment.location') }}" class="btn btn-primary">
                    BOOK APPOINTMENT
                </a>
        
                <button class="btn btn-view" onclick="showLoyaltyCard()">
                    VIEW LOYALTY CARD
                </button>
            </div>
        </div>
        @endif     
        

        <!-- Loyalty Card -->
        <div id="loyaltyCardContent" style="display:none">
            <div class="loyalty-card">
                <div class="title">
                    <h2>Loyalty Card</h2>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </div>
                <!-- Reset the loyalty card if they booked another appointment after the last discount-->
                @php
                    if ($completedAppointments >= 12) {
                        $currentProgress = 12; // Show full card
                    } else {
                        $currentProgress = $completedAppointments;
                    }
                @endphp
                <div class="loyalty-grid">
                    <!-- First row -->
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="loyalty-slot {{ $currentProgress >= $i ? 'filled' : '' }}">{{ $i }}</div>
                    @endfor
                    <div class="loyalty-slot special {{ $currentProgress >= 6 ? 'filled' : '' }}">50% OFF</div>

                    <!-- Second row -->
                    @for ($i = 7; $i <= 11; $i++)
                        <div class="loyalty-slot {{ $currentProgress >= $i ? 'filled' : '' }}">{{ $i }}</div>
                    @endfor
                    <div class="loyalty-slot special {{ $currentProgress >= 12 ? 'filled' : '' }}">50% OFF</div>
                </div>
                <div class="bottom-title">
                    <h1>Jamir Barbershop</h1>
                </div>
            </div>
        </div>
</x-app-layout>

<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #fff;
    font-family: 'Inter', sans-serif;
">
    <div class="spinner" style="
        border: 6px solid #f3f3f3;
        border-top: 6px solid #E8B931; 
        border-radius: 50%;
        width: 60px; height: 60px;
        animation: spin 1s linear infinite;
    "></div>
    <p style="margin-top: 20px; font-size: 1.2rem;">Cancelling appointment...</p>
</div>


<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>


<script> //prevents going back to the log in page after log in
    window.history.pushState(null, document.title, window.location.href);
    

    window.addEventListener('popstate', function () {
        window.history.pushState(null, document.title, window.location.href);
    });

    function confirmCancel() {
        Swal.fire({
            title: 'Cancel Appointment?',
            text: 'Please provide a reason (optional):',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Enter reason here...',
            inputAttributes: {
                'aria-label': 'Cancel reason'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, cancel it',
            cancelButtonText: 'No',
            preConfirm: (reason) => {
                // attach reason to hidden input
                document.getElementById('cancel_reason_input').value = reason || '';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('loadingOverlay').style.display = 'flex';
                document.getElementById('cancelForm').submit();
            }
        });
    }

    function showLoyaltyCard() {
        Swal.fire({
            title: '',
            html: document.getElementById('loyaltyCardContent').innerHTML,
            width: '600px',
            customClass: {
                popup: 'swal-loyalty-card-popup'
            },
            showCloseButton: true,
            showConfirmButton: false,
        });
    }
</script>