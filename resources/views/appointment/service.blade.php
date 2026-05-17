<!-- resources/views/appointment/service.blade.php -->
<x-layout>
    @section('title', 'Select Service')
    @vite('resources/css/appointment-css/service.css')
    <!-- Instrument Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <div class="main">
        <div class="container">
            <h2 class="section-heading">Select Services</h2>
            <form action="{{ route('appointment.datetime-barber') }}" method="POST" id="main-form">
                @csrf
                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
        
                <div class="form-group">
                    @foreach($services as $service)
                        <div class="form-check service-row">
                            <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->id }}" id="service{{ $service->id }}">
                            <label class="form-check-label" for="service{{ $service->id }}">
                                {{ $service->name }}
                            </label>
                            @if($service->dropdown_options)
                                @php $options = $service->dropdown_options; @endphp
                                <select id="dropdown{{ $service->id }}" name="dropdown_option_{{ $service->id }}" style="display:none;">
                                    <option value="" disabled selected>Select option</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>
        
                <div id="service-error" class="text-danger mb-3" style="display: none; color: red;">
                    Please select at least one service.
                </div>
        
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                
            </form>
        
            <form action="{{ route('appointment.location') }}" method="GET" class="button-group" style="margin-top: 1rem;">
                <button type="submit" class="btn btn-secondary">Back to Location</button>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($services as $service)
            @if($service->dropdown_options)
                var checkbox{{ $service->id }} = document.getElementById('service{{ $service->id }}');
                var dropdown{{ $service->id }} = document.getElementById('dropdown{{ $service->id }}');
                dropdown{{ $service->id }}.disabled = true;
                checkbox{{ $service->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        dropdown{{ $service->id }}.style.display = 'inline-block';
                        dropdown{{ $service->id }}.disabled = false;
                        dropdown{{ $service->id }}.setAttribute('required', 'required');
                    } else {
                        dropdown{{ $service->id }}.style.display = 'none';
                        dropdown{{ $service->id }}.disabled = true;
                        dropdown{{ $service->id }}.selectedIndex = 0;
                        dropdown{{ $service->id }}.removeAttribute('required');
                    }
                });
            @endif
        @endforeach
    
        var form = document.getElementById('main-form');
        var errorDiv = document.getElementById('service-error');
    
        form.addEventListener('submit', function(e) {
            var checked = form.querySelectorAll('input[name="services[]"]:checked').length;
            if (checked === 0) {
                e.preventDefault();
                errorDiv.style.display = 'block';
                return false;
            } else {
                errorDiv.style.display = 'none';
            }
    
            @foreach($services as $service)
                @if($service->dropdown_options)
                    var checkbox = document.getElementById('service{{ $service->id }}');
                    var dropdown = document.getElementById('dropdown{{ $service->id }}');
                    if (checkbox.checked) {
                        dropdown.disabled = false;
                        dropdown.style.display = 'inline-block';
                    }
                @endif
            @endforeach
        });
    });
    </script>
</x-layout>

