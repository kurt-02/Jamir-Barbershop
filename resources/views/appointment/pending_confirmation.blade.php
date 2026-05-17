<x-header>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <div class="confirmation-box">
        <h1>Confirm Your Appointment</h1>
        <div class="message">
            <p>
                Hi <strong>{{ $activeAppointment->client_name }}</strong>, you have a pending appointment on 
                <strong>{{ \Carbon\Carbon::parse($activeAppointment->appointment_date)->format('F j, Y') }}</strong> 
                at <strong>{{ \Carbon\Carbon::parse($activeAppointment->appointment_time)->format('g:i A') }}</strong>.
            </p>
            <p>
                Please check your email or phone to confirm the appointment. This slot is not yet reserved until you confirm it.
            </p>
        </div>

        <div class="button-group">
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <style>
        .confirmation-box {
            max-width: 600px;
            margin: 4rem auto;
            padding: 2rem;
            background-color: #121212;
            border: 1px solid #333;
            color: white;
            position: relative;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            min-height: 50vh;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
        }

        .confirmation-box h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-family: 'Instrument Serif', serif;
            font-weight: 300;
            color: #E8B931;
        }

        .confirmation-box .message {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .confirmation-box .message > p:first-of-type {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #ccc;
        }

        .confirmation-box .message > p:nth-of-type(2) {
            font-size: 1rem;
            color: white;
        }

        .button-group {
            margin-top: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
            font-family: "Inter", sans-serif;
        }

        .btn-primary {
            background-color: transparent;
            border: 2px solid #E8B931;
            color: #E8B931;
        }

        .btn-primary:hover {
            background-color: #E8B931;
            color: black;
        }

        /* Tablet (<= 1024px) */
        @media (max-width: 1024px) {
            .confirmation-box {
                max-width: 90%;
                padding: 1.5rem;
            }

            .confirmation-box h1 {
                font-size: 1.75rem;
            }

            .confirmation-box .message p {
                font-size: 1rem;
            }

            .btn {
                font-size: 1rem;
                padding: 0.6rem 1.5rem;
            }
        }

        /* Mobile (<= 768px) */
        @media (max-width: 768px) {
            .confirmation-box {
                margin: 2rem auto;
                padding: 1rem;
                min-height: auto;
            }

            .confirmation-box h1 {
                font-size: 1.5rem;
                text-align: center;
            }

            .confirmation-box .message > p {
                font-size: 0.95rem;
                text-align: center;
            }

            .button-group {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                width: 100%;
                text-align: center;
                font-size: 1rem;
                padding: 0.8rem;
            }
        }

        /* Extra small devices (<= 480px) */
        @media (max-width: 480px) {
            .confirmation-box h1 {
                font-size: 1.25rem;
            }

            .confirmation-box .message > p {
                font-size: 0.85rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.7rem;
            }
        }
    </style>
</x-header>
