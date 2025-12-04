<x-guest-layout>
<div class="bg-base-100 p-6 sm:p-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-upsi-dark">Students Verification</h1>
                <span class="badge" style="background-color:#44d437;color:#1F2937;">Pelajar Aktif</span>
            </div>
            <p class="text-gray-600">Complete verification to start your services.</p>
        </div>

        <!-- Steps -->
        <ul class="steps w-full">
            <li class="step step-primary">Address Verification</li>
            <li class="step">Upload Photo</li>
            <li class="step">Live Selfie</li>
        </ul>

        <!-- Step 1: Address -->
        <div class="card bg-white border border-gray-200 mt-6 p-6">
            <h2 class="card-title text-upsi-blue">Step 1: Verify Address</h2>
            <p class="text-gray-600">Detect your location or enter your full address. Must be around Tanjung Malim / UPSI.</p>
            
            <div class="mt-4 flex flex-col space-y-3">
                <button id="detect_location_btn" class="btn btn-primary">Detect My Location</button>
                <p class="text-gray-600">OR</p>
                <input type="text" id="manual_address" placeholder="Enter your full address" class="input input-bordered w-full max-w-md" />
                <button id="verify_manual_btn" class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Verify Address Manually</button>
                <p id="location_status" class="mt-2 text-gray-600"></p>
            </div>
        </div>

       <!-- Step 2: Upload Profile Photo -->
<div class="card bg-white border border-gray-200 mt-6 p-6">
    <h2 class="card-title text-upsi-blue">Step 2: Upload Profile Photo</h2>

    <form id="upload_photo_form" method="POST" action="{{ route('students_verification.upload') }}" enctype="multipart/form-data">
        @csrf

        <div class="flex items-center space-x-4">
            <!-- Preview -->
            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden">
                <img id="profile-preview"
                    src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    class="w-full h-full object-cover" alt="Profile Preview">
            </div>

            <!-- File Input -->
            <input type="file" name="profile_photo" id="profile_photo_input" accept="image/*"
                class="file-input file-input-bordered w-full max-w-md" />
        </div>

        @error('profile_photo')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Upload & Save</button>
        </div>
    </form>
</div>

<script>
document.getElementById('profile_photo_input').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(ev){
            document.getElementById('profile-preview').src = ev.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>


        <!-- Step 3: Live Selfie -->
        <div class="card bg-white border border-gray-200 mt-6 p-6">
            <h2 class="card-title text-upsi-blue">Step 3: Live Selfie Check</h2>
            <p class="text-gray-600">Complete a live selfie match to confirm identity.</p>

            <div class="bg-gray-100 rounded-xl border border-gray-200 p-6 flex flex-col items-center">
                <video id="camera_preview" autoplay playsinline class="w-80 h-60 rounded-md border mb-4"></video>
                <canvas id="snapshot_canvas" class="hidden w-80 h-60 rounded-md border mb-4"></canvas>
                <div class="flex space-x-4">
                    <button id="start_camera" class="btn btn-primary">Start Camera</button>
                    <button id="take_snapshot" class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Take Snapshot</button>
                    <button id="confirm_snapshot" class="btn btn-success hidden">Confirm & Upload</button>
                </div>
                <p id="camera_status" class="mt-2 text-gray-600"></p>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('onboarding.register') }}" class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Back</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const UPSI_LAT = 3.7832;
const UPSI_LNG = 101.5927;
const RADIUS_KM = 5;

// Distance calc
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2){
    var R = 6371;
    var dLat = (lat2-lat1) * Math.PI/180;
    var dLon = (lon2-lon1) * Math.PI/180;
    var a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
    var c = 2*Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R*c;
}

function addressVerified(){
    Swal.fire({icon:'success',title:'Address Verified!',text:'Ready to help people!',timer:3000,showConfirmButton:false});
    document.querySelectorAll('.card')[1].scrollIntoView({behavior:'smooth'});
}

// Detect GPS
document.getElementById('detect_location_btn').addEventListener('click', function(){
    const statusEl = document.getElementById('location_status');
    if(navigator.geolocation){
        statusEl.textContent = "Detecting location...";
        navigator.geolocation.getCurrentPosition(function(position){
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const distance = getDistanceFromLatLonInKm(lat,lng,UPSI_LAT,UPSI_LNG);
            if(distance <= RADIUS_KM){
                addressVerified();
            } else {
                Swal.fire({icon:'error', title:'Verification Failed', text:'You must be around Tanjung Malim / UPSI.'});
            }
        }, function(err){
            Swal.fire({icon:'error',title:'Location Error',text:'Unable to detect location. Please allow location access.'});
        });
    } else {
        Swal.fire({icon:'error',title:'Unsupported',text:'Geolocation not supported.'});
    }
});

// Manual address
document.getElementById('verify_manual_btn').addEventListener('click', function(){
    const addr = document.getElementById('manual_address').value.trim().toLowerCase();
    if(addr.length < 10){
        Swal.fire({icon:'error',title:'Verification Failed',text:'Please enter full address.'});
        return;
    }
    if(addr.includes('tanjung malim') || addr.includes('upsi')){
        addressVerified();
    } else {
        Swal.fire({icon:'error',title:'Verification Failed',text:'Address must be around Tanjung Malim / UPSI.'});
    }
});

// Profile preview
document.getElementById('profile_photo_input').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(ev){ document.getElementById('profile-preview').src = ev.target.result; }
        reader.readAsDataURL(file);
    }
});

// Camera
let stream, snapshotTaken=false;
document.getElementById('start_camera').addEventListener('click', async ()=>{
    const video = document.getElementById('camera_preview');
    stream = await navigator.mediaDevices.getUserMedia({video:true});
    video.srcObject = stream;
});
document.getElementById('take_snapshot').addEventListener('click', ()=>{
    const video = document.getElementById('camera_preview');
    const canvas = document.getElementById('snapshot_canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video,0,0,canvas.width,canvas.height);
    canvas.classList.remove('hidden');
    snapshotTaken = true;
    document.getElementById('confirm_snapshot').classList.remove('hidden');
});
document.getElementById('confirm_snapshot').addEventListener('click', ()=>{
    if(snapshotTaken){
        Swal.fire({icon:'success',title:'Live Selfie Complete!',timer:2500,showConfirmButton:false})
        .then(()=>{ window.location.href="{{ route('students.create') }}"; });
    }
});
</script>
</x-guest-layout>
