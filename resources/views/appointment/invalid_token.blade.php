<x-header>
    <div class="invalid-token">
        <h1>Invalid or Expired Link</h1>
        <p>
            Sorry, the appointment confirmation link you used is either 
            <strong>invalid</strong> or has <strong>already expired</strong>.  
            Please check your email for the latest link or book a new appointment.
        </p>

        <div class="button-group">
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <style>
        .invalid-token {
            max-width: 600px;
            margin: 4rem auto;
            padding: 2rem;
            text-align: center;
            background-color: #121212;
            border: 1px solid #333;
            color: white;
            font-family: "Inter", sans-serif;
            min-height: 40vh;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
        }

        .invalid-token h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-family: 'Instrument Serif', serif;
            font-weight: 300;
            color: #dc2626; /* red error accent */
        }

        .invalid-token p {
            font-size: 1.1rem;
            color: #ccc;
            margin-bottom: 2rem;
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

        /* Tablet (≤1024px) */
        @media (max-width: 1024px) {
            .invalid-token {
                max-width: 90%;
                padding: 1.5rem;
            }

            .invalid-token h1 {
                font-size: 1.75rem;
            }

            .invalid-token p {
                font-size: 1rem;
            }

            .btn {
                font-size: 1rem;
                padding: 0.6rem 1.5rem;
            }
        }

        /* Mobile (≤768px) */
        @media (max-width: 768px) {
            .invalid-token {
                margin: 2rem auto;
                padding: 1rem;
                min-height: auto;
            }

            .invalid-token h1 {
                font-size: 1.5rem;
            }

            .invalid-token p {
                font-size: 0.95rem;
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

        /* Extra small devices (≤480px) */
        @media (max-width: 480px) {
            .invalid-token h1 {
                font-size: 1.25rem;
            }

            .invalid-token p {
                font-size: 0.85rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.7rem;
            }
        }
    </style>
</x-header>
