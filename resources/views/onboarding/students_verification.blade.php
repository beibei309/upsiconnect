<x-guest-layout>
    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Verify Your Account</h1>
                <p class="mt-2 text-slate-500">Complete these 3 simple steps to become a verified provider on S2U.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="hidden lg:block w-1/4">
                    <div class="sticky top-24 space-y-8">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-indigo-200">1</div>
                                <div class="h-full w-0.5 bg-indigo-100 my-2"></div>
                            </div>
                            <div class="pt-1">
                                <h3 class="font-bold text-indigo-900">Address Verification</h3>
                                <p class="text-xs text-slate-500">Confirm location</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm">2</div>
                                <div class="h-full w-0.5 bg-slate-100 my-2"></div>
                            </div>
                            <div class="pt-1">
                                <h3 class="font-medium text-slate-500">Profile Photo</h3>
                                <p class="text-xs text-slate-400">Upload clear image</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm">3</div>
                            </div>
                            <div class="pt-1">
                                <h3 class="font-medium text-slate-500">Live Selfie</h3>
                                <p class="text-xs text-slate-400">Identity check</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 space-y-8">

                    <div id="step1" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden scroll-mt-24">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">Address Verification</h2>
                            </div>
                            
                            <p class="text-slate-600 mb-6 text-sm">To ensure safety, service providers must be located within the Tanjung Malim / UPSI area.</p>

                            <div class="space-y-4">
                                <button id="detect_location_btn" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-xl font-medium transition-all shadow-md shadow-indigo-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Detect My Location
                                </button>
                                
                                <div class="relative flex py-2 items-center">
                                    <div class="flex-grow border-t border-slate-200"></div>
                                    <span class="flex-shrink-0 mx-4 text-slate-400 text-xs font-semibold uppercase">Or verify manually</span>
                                    <div class="flex-grow border-t border-slate-200"></div>
                                </div>

                                <div class="flex gap-2">
                                    <input type="text" id="manual_address" placeholder="Enter full address (e.g., Kolej Aminuddin Baki, UPSI)" class="flex-1 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <button id="verify_manual_btn" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 rounded-xl font-medium text-sm transition-colors">Verify</button>
                                </div>
                                <p id="location_status" class="text-sm font-medium h-5"></p>
                            </div>
                        </div>
                    </div>

                    <div id="step2" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden scroll-mt-24 opacity-50 pointer-events-none transition-all duration-500">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">Upload Profile Photo</h2>
                            </div>

                            <form id="upload_photo_form" method="POST" action="{{ route('students_verification.upload') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    <div class="relative group">
                                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-slate-100">
                                            <img id="profile-preview" src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 w-full">
                                        <label class="block w-full">
                                            <span class="sr-only">Choose profile photo</span>
                                            <input type="file" name="profile_photo" id="profile_photo_input" accept="image/*" class="block w-full text-sm text-slate-500
                                              file:mr-4 file:py-2.5 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100 transition-all
                                            "/>
                                        </label>
                                        <p class="mt-2 text-xs text-slate-400">JPG, PNG or GIF. Max 4MB.</p>
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-medium transition-all">Save Photo</button>
                            </form>
                        </div>
                    </div>

                    <div id="step3" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden scroll-mt-24 opacity-50 pointer-events-none transition-all duration-500">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900">Live Selfie Check</h2>
                            </div>

                            <p class="text-slate-600 mb-6 text-sm">We need to verify that you are a real person. Please take a live selfie.</p>

                            <div class="bg-slate-900 rounded-2xl p-4 overflow-hidden relative">
                                <div class="aspect-video bg-black rounded-xl overflow-hidden relative flex items-center justify-center">
                                    <video id="camera_preview" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100 hidden"></video>
                                    <canvas id="snapshot_canvas" class="w-full h-full object-cover hidden"></canvas>
                                    
                                    <div id="camera_placeholder" class="text-slate-600 flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm">Camera inactive</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-center gap-3">
                                    <button id="start_camera" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-medium text-sm transition-all shadow-lg shadow-indigo-900/50">
                                        Start Camera
                                    </button>
                                    <button id="take_snapshot" class="hidden bg-white text-slate-900 px-6 py-2 rounded-full font-bold text-sm hover:bg-slate-100 transition-all">
                                        Capture
                                    </button>
                                    <button id="retake_snapshot" class="hidden bg-slate-700 text-white px-6 py-2 rounded-full font-medium text-sm hover:bg-slate-600 transition-all">
                                        Retake
                                    </button>
                                    <button id="confirm_snapshot" class="hidden bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-bold text-sm transition-all shadow-lg shadow-green-900/30">
                                        Confirm & Upload
                                    </button>
                                </div>
                                <p id="camera_status" class="text-center text-slate-400 text-xs mt-3 h-4"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6">
                        <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Dashboard
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // --- GLOBAL VARIABLES ---
    const UPSI_LAT = 3.7832;
    const UPSI_LNG = 101.5927;
    const RADIUS_KM = 5;
    let stream = null;
    let selfieDataUrl = null;

    // Unlock next steps logic
    function unlockStep(stepId) {
        const step = document.getElementById(stepId);
        step.classList.remove('opacity-50', 'pointer-events-none');
        step.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // --- STEP 1: ADDRESS VERIFICATION ---
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2){
        var R = 6371; 
        var dLat = (lat2-lat1) * Math.PI/180;
        var dLon = (lon2-lon1) * Math.PI/180;
        var a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
        var c = 2*Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R*c;
    }

    function addressVerified(){
        const statusEl = document.getElementById('location_status');
        statusEl.textContent = "Location Verified Successfully!";
        statusEl.className = "text-sm font-bold text-green-600 mt-2";
        
        Swal.fire({
            icon: 'success',
            title: 'Verified!',
            text: 'Address confirmed. Proceeding to photo upload.',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            unlockStep('step2');
        });
    }

    document.getElementById('detect_location_btn').addEventListener('click', function(){
        const btn = this;
        const originalText = btn.innerHTML;
        const statusEl = document.getElementById('location_status');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Detecting...';
        
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const distance = getDistanceFromLatLonInKm(lat,lng,UPSI_LAT,UPSI_LNG);
                
                btn.disabled = false;
                btn.innerHTML = originalText;

                if(distance <= RADIUS_KM){
                    addressVerified();
                } else {
                    statusEl.textContent = "Location failed. You are outside the allowed area.";
                    statusEl.className = "text-sm font-bold text-red-500 mt-2";
                    Swal.fire({icon:'error', title:'Verification Failed', text:'You must be around Tanjung Malim / UPSI.'});
                }
            }, function(err){
                btn.disabled = false;
                btn.innerHTML = originalText;
                Swal.fire({icon:'error',title:'Location Error',text:'Unable to detect location. Please allow location access.'});
            });
        } else {
            Swal.fire({icon:'error',title:'Unsupported',text:'Geolocation not supported.'});
        }
    });

    document.getElementById('verify_manual_btn').addEventListener('click', function(){
        const addr = document.getElementById('manual_address').value.trim().toLowerCase();
        if(addr.length < 10){
            Swal.fire({icon:'warning',title:'Invalid Address',text:'Please enter a complete address.'});
            return;
        }
        if(addr.includes('tanjung malim') || addr.includes('upsi')){
            addressVerified();
        } else {
            Swal.fire({icon:'error',title:'Location Error',text:'Address must be around Tanjung Malim / UPSI.'});
        }
    });

    // --- STEP 2: PROFILE PREVIEW ---
    const photoInput = document.getElementById('profile_photo_input');
    photoInput.addEventListener('change', function(e){
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(ev){ document.getElementById('profile-preview').src = ev.target.result; }
            reader.readAsDataURL(file);
        }
    });

    // Intercept form submit to unlock next step instead of refresh (for demo flow)
    // Ideally, the server response should redirect back with a success flag.
    // For now, let's assume the user clicks "Save Photo" and it submits. 
    // If you want purely JS flow first:
    const photoForm = document.getElementById('upload_photo_form');
    photoForm.addEventListener('submit', function(e){
        // In a real app, this would submit to server. 
        // If server redirects back, we can check session flash messages to unlock Step 3.
        // For UX smoothness in this demo, let's assume success if file selected.
        if(photoInput.files.length > 0) {
            // Let the form submit naturally. 
            // NOTE: To make the flow continuous without reload, use AJAX/Fetch here.
        }
    });
    
    // Check if user already has a photo (Unlock Step 3 automatically if photo exists)
    @if(auth()->user()->profile_photo_path)
        unlockStep('step2'); // Keep step 2 visible
        unlockStep('step3'); // Unlock step 3
    @endif


    // --- STEP 3: LIVE CAMERA ---
    const video = document.getElementById('camera_preview');
    const canvas = document.getElementById('snapshot_canvas');
    const placeholder = document.getElementById('camera_placeholder');
    
    const startBtn = document.getElementById('start_camera');
    const takeBtn = document.getElementById('take_snapshot');
    const retakeBtn = document.getElementById('retake_snapshot');
    const confirmBtn = document.getElementById('confirm_snapshot');

    startBtn.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.classList.remove('hidden');
            placeholder.classList.add('hidden');
            
            startBtn.classList.add('hidden');
            takeBtn.classList.remove('hidden');
            document.getElementById('camera_status').textContent = "Look at the camera and smile!";
        } catch (err) {
            Swal.fire({icon:'error', title:'Camera Error', text:'Unable to access camera. Please check permissions.'});
        }
    });

    takeBtn.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        // Flip horizontally to match video mirror effect
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        selfieDataUrl = canvas.toDataURL('image/png');

        video.classList.add('hidden');
        canvas.classList.remove('hidden');
        takeBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        confirmBtn.classList.remove('hidden');
        document.getElementById('camera_status').textContent = "Photo captured. Confirm to upload.";
    });

    retakeBtn.addEventListener('click', () => {
        canvas.classList.add('hidden');
        video.classList.remove('hidden');
        retakeBtn.classList.add('hidden');
        confirmBtn.classList.add('hidden');
        takeBtn.classList.remove('hidden');
        document.getElementById('camera_status').textContent = "Look at the camera and smile!";
    });

    confirmBtn.addEventListener('click', () => {
        if(!selfieDataUrl) return;

        Swal.fire({
            title: 'Uploading...',
            html: 'Verifying identity...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch("{{ route('students_verification.upload_selfie') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ selfie_image: selfieDataUrl })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                Swal.fire({
                    icon:'success', 
                    title:'Identity Verified!', 
                    text: 'Redirecting to your profile setup...', 
                    timer: 2000, 
                    showConfirmButton: false
                }).then(() => { 
                    if(stream) stream.getTracks().forEach(track => track.stop());
                    window.location.href = "{{ route('students.create') }}"; 
                });
            } else {
                Swal.fire({icon:'error', title:'Upload Failed', text: 'Please try again.'});
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({icon:'error', title:'Error', text: 'Something went wrong.'});
        });
    });
    </script>
</x-guest-layout>