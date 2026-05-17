<x-header>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <div class="confirmation-already">
        <h1>Already Confirmed</h1>
        <p>
            Hi <strong>{{ $appointment->client_name }}</strong>, you’ve already confirmed your booking on 
            <strong>{{ $appointment->appointment_date }} at {{ $appointment->appointment_time }}</strong>.
        </p>
        <div class="button-group">
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <style>
        .confirmation-already {
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
            min-height: 40vh;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            text-align: center;
        }

        .confirmation-already h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-family: 'Instrument Serif', serif;
            font-weight: 300;
            color: #16a34a; /* Green success accent */
        }

        .confirmation-already p {
            font-size: 1.1rem;
            color: #ccc;
            margin-bottom: 2rem;
            font-family: "Inter", sans-serif;
        }

        .button-group {
            margin-top: auto;
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
            .confirmation-already {
                max-width: 90%;
                padding: 1.5rem;
            }

            .confirmation-already h1 {
                font-size: 1.75rem;
            }

            .confirmation-already p {
                font-size: 1rem;
            }

            .btn {
                font-size: 1rem;
                padding: 0.6rem 1.5rem;
            }
        }

        /* Mobile (<= 768px) */
        @media (max-width: 768px) {
            .confirmation-already {
                margin: 2rem auto;
                padding: 1rem;
                min-height: auto;
            }

            .confirmation-already h1 {
                font-size: 1.5rem;
                text-align: center;
            }

            .confirmation-already p {
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
            .confirmation-already h1 {
                font-size: 1.25rem;
            }

            .confirmation-already p {
                font-size: 0.85rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.7rem;
            }
        }
    </style>
</x-header>
