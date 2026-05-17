<x-header>
    @section('title', 'Jamir Barbershop - Haircut Recommender')
</x-header>

<!-- Instrument Serif Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
@vite('resources/css/face-upload.css')

<div class="main-content">
    <div class="title">
        <h1>Haircut Recommendations</h1>
    </div>
    
    <div class="main">
        <div class="photo">
            <img src="{{ asset('/images/photos/ai-face.jpg')}}" alt="Sample Cut">
        </div>
        <div class="right-side">
            <h2>How it works.</h2>
            <p>Simply upload your photo and our system will automatically analyze your face shape to suggest the best haircut styles, 
                based on input from our own barbers.
            </p>
            @if ($errors->any())
                <div style="color:red;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form id="analyzeForm" action="{{ route('face.analyze') }}" method="POST" enctype="multipart/form-data" class="buttons">
                @csrf
                <label for="file-upload" id="file-label" class="custom-file-label">Choose File</label>
                <input id="file-upload" type="file" name="image" accept="image/*">
                <button type="submit">Analyze</button>
            </form>
            
            <div class="note">
                <p>Note: Your photo is strictly used for face shape detection only and will never be saved, shared, or viewed for any 
                    other purposes. Also, the facial recognition results may not be 100% accurate and should not be relied upon as definitive.
                </p>
            </div>
        </div>
    </div>
</div>


{{-- Fullscreen Loader --}}
<div id="loadingOverlay">
    <div class="loader"></div>
    <p>Analyzing your face, please wait...</p>
</div>

<style>
    /* Fullscreen overlay */
    body {
        background: linear-gradient(to bottom, rgba(0,0,0,0) 20%, rgba(0,0,0,0.9) 100%), url('../images/backgrounds/hair-reco-bg.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center top; 
        background-attachment: fixed; 
        color: white;
    }

    .title{
        border-bottom: 2px solid white;
    }

    .title h1{
        font-family: 'Instrument Serif', serif;
        font-optical-sizing: auto;
        font-weight: 300;
        font-style: normal;
        font-variation-settings:
        "wdth" 100;
        font-size: 5rem;
    }

    .main-content{
        padding: 1rem 3rem;
    }

    .main {
        display: flex;
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        margin-top: 2rem;
    }

    .right-side{
        margin-left: 3rem;
        margin-top: 1rem;
        width: 60%;
    }

    .right-side h2{
        font-size: 2.5rem;
    }

    .right-side p{
        font-size: 1.2rem;
        font-weight: 200;
    }

    .note{
        margin-top: 3rem;
    }

    .photo{
        width: 30%;
        height: auto;
    }

    .buttons{
        display: flex;
        flex-direction: column;
        width: 50%;
        margin-top: 2rem;
    }

    input[type="file"] {
        display: none; 
    }

    .custom-file-label {
        display: inline-block;
        padding: 12px 32px;
        border: 2px solid white;
        border-radius: 50px;
        background: transparent;
        color: white;
        font-family: "Inter", sans-serif;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
    }

    .custom-file-label:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    button[type="submit"] {
        display: inline-block;
        padding: 12px 32px;
        border: 2px solid #E8B931; /* gold */
        border-radius: 50px;
        background: transparent;
        color: #E8B931;
        font-family: "Inter", sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }

    button[type="submit"]:hover {
        background: #E8B931;
        color: black;
    }

    #loadingOverlay {
        display: none; 
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        text-align: center;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: "Inter", sans-serif;
        color: white;
    }

    #loadingOverlay .loader {
        border: 6px solid rgba(255, 255, 255, 0.2);
        border-top: 6px solid #E8B931;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    #loadingOverlay p {
        font-size: 1rem;
        font-weight: 500;
        color: #fff;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Tablet view (up to 1024px wide) */
    @media (max-width: 1024px) {
        .main {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .photo {
            width: 50%;
            margin-bottom: 2rem;
        }

        .right-side {
            width: 90%;
            margin-left: 0;
        }

        .buttons {
            width: 70%;
            margin: 2rem auto 0 auto;
        }

        .title h1 {
            font-size: 3.5rem;
        }

        .right-side h2 {
            font-size: 2rem;
        }

        .right-side p {
            font-size: 1.1rem;
        }
    }

    /* Mobile view (up to 768px wide) */
    @media (max-width: 768px) {
        body {
            background-position: center;
            padding: 0 1rem;
        }

        .title{
            text-align: center;
        }

        .title h1 {
            font-size: 2.5rem;
        }

        .photo {
            width: 80%;
        }

        .right-side {
            width: 100%;
        }

        .buttons {
            width: 100%;
        }

        .custom-file-label,
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
        }

        .right-side h2 {
            font-size: 1.8rem;
        }

        .right-side p {
            font-size: 1rem;
        }
    }

</style>

<script>
    document.getElementById("file-upload").addEventListener("change", function() {
        const fileName = this.files[0] ? this.files[0].name : "Choose File";
        document.getElementById("file-label").textContent = fileName;
    });

    document.getElementById("analyzeForm").addEventListener("submit", function (e) {
        e.preventDefault(); 

        const fileInput = document.getElementById("file-upload");

        if (!fileInput.files.length) {
            alert("Please upload a photo before analyzing.");
            return; 
        }

        document.getElementById("loadingOverlay").style.display = "flex";
        this.submit();
    });
</script>


