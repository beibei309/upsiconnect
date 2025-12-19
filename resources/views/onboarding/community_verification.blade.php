<x-guest-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Community Verification</h1>
                <p class="mt-2 text-slate-500">Complete these 3 steps to verify your identity and ensure community safety.</p>
            </div>

            <!-- STATUS BANNER -->
            @if(auth()->user()->verification_status === 'pending' && auth()->user()->verification_document_path)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8 text-center">
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-yellow-800 mb-2">Verification Under Review</h2>
                    <p class="text-yellow-700 max-w-lg mx-auto">We have received your details. Our admin team is reviewing your profile photo, selfie, and documents. You will be notified via email once approved.</p>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-indigo-600 hover:text-indigo-800 font-medium text-sm underline">Log Out</button>
                        </form>
                    </div>
                </div>
            @elseif(auth()->user()->verification_status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8 text-center">
                    <h3 class="font-bold text-red-800 text-lg">Verification Rejected</h3>
                    <p class="text-red-600 mt-1">Please re-submit valid documents matching your profile.</p>
                </div>
            @endif

            @if(auth()->user()->verification_status !== 'pending' || !auth()->user()->verification_document_path || auth()->user()->verification_status === 'rejected')
            <div class="space-y-8">

                <!-- STEP 1: PROFILE PHOTO -->
                <div id="step1" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">1</div>
                            <h2 class="text-xl font-bold text-slate-900">Upload Profile Photo</h2>
                        </div>

                        <form id="profile_form" action="{{ route('onboarding.community.upload_photo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-slate-100 shadow-lg bg-slate-50">
                                    <img id="profile-preview" src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="file" name="profile_photo" id="profile_photo_input" accept="image/*" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all"/>
                                    <p class="mt-2 text-xs text-slate-400">Clear face photo. JPG/PNG, Max 4MB.</p>
                                </div>
                                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-slate-800 transition-all">Save Photo</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STEP 2: LIVE SELFIE -->
                <div id="step2" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden {{ auth()->user()->profile_photo_path ? '' : 'opacity-50 pointer-events-none' }}">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">2</div>
                            <h2 class="text-xl font-bold text-slate-900">Live Selfie Check</h2>
                        </div>
                        
                        <p class="text-slate-600 mb-4 text-sm">To prove you are a real person, please follow the specific gesture instruction below.</p>

                        <div class="bg-slate-900 rounded-2xl p-4 relative overflow-hidden">
                            <!-- Challenge Instruction Banner -->
                            <div id="challenge_banner" class="hidden absolute top-4 left-0 w-full z-10 text-center px-4">
                                <div class="bg-yellow-400 text-slate-900 font-bold py-2 px-4 rounded-full inline-block shadow-lg border-2 border-yellow-200 animate-pulse">
                                    Target Gesture: <span id="challenge_text" class="uppercase">Retrieving...</span>
                                </div>
                            </div>

                            <div class="aspect-video bg-black rounded-xl overflow-hidden relative flex items-center justify-center">
                                <video id="camera_preview" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100 hidden"></video>
                                <canvas id="snapshot_canvas" class="w-full h-full object-cover hidden"></canvas>
                                
                                <div id="camera_placeholder" class="text-slate-500 flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-sm">Camera inactive</span>
                                </div>
                                
                                <!-- Guide Overlay -->
                                <div id="face_guide" class="absolute inset-0 border-4 border-white/30 rounded-[50%] w-48 h-64 m-auto hidden pointer-events-none"></div>
                            </div>

                            <div class="mt-4 flex justify-center gap-3">
                                <button id="start_camera" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-medium text-sm transition-all shadow-lg">Start Camera</button>
                                <button id="take_snapshot" class="hidden bg-white text-slate-900 px-6 py-2 rounded-full font-bold text-sm hover:bg-slate-100 transition-all">Capture Photo</button>
                                <button id="retake_snapshot" class="hidden bg-slate-700 text-white px-6 py-2 rounded-full font-medium text-sm hover:bg-slate-600 transition-all">Retake</button>
                                <button id="confirm_snapshot" class="hidden bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-bold text-sm transition-all shadow-lg">Confirm & Upload</button>
                            </div>
                        </div>
                        <p id="selfie_status" class="text-center text-sm font-medium text-green-600 mt-2 h-5">{{ auth()->user()->selfie_media_path ? 'Selfie Uploaded! ✅' : '' }}</p>
                    </div>
                </div>

                <!-- STEP 3: DOCUMENT UPLOAD -->
                <div id="step3" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden {{ auth()->user()->selfie_media_path ? '' : 'opacity-50 pointer-events-none' }}">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">3</div>
                            <h2 class="text-xl font-bold text-slate-900">Upload Proof Document</h2>
                        </div>

                        <form method="POST" action="{{ route('onboarding.community.submit_doc') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <h3 class="font-bold text-blue-900 text-xs uppercase tracking-wider mb-2">Accepted Documents (Private & Secure)</h3>
                                <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
                                    <li>Recent Utility Bill (Water/Electric)</li>
                                    <li>Work Staff ID (if working in Tanjung Malim)</li>
                                    <li>Business Registration (SSM)</li>
                                </ul>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Select Document (Image or PDF)</label>
                                <input type="file" name="verification_document" accept=".jpg,.jpeg,.png,.pdf" required
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-slate-200 rounded-lg"/>
                            </div>

                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-medium transition-all shadow-lg">
                                Submit Final Verification
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @endif
            
            <div class="text-center mt-8 pb-8">
                 <a href="{{ route('dashboard') }}" class="text-sm text-slate-400 hover:text-slate-600">Back to Dashboard</a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // --- STEP 1: PROFILE PREVIEW ---
        document.getElementById('profile_photo_input').addEventListener('change', function(e){
            const file = e.target.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(ev){ document.getElementById('profile-preview').src = ev.target.result; }
                reader.readAsDataURL(file);
            }
        });

        // --- STEP 2: CAMERA LOGIC ---
        let stream = null;
        let selfieDataUrl = null;
        let currentChallenge = "";
        
        const video = document.getElementById('camera_preview');
        const canvas = document.getElementById('snapshot_canvas');
        const placeholder = document.getElementById('camera_placeholder');
        const startBtn = document.getElementById('start_camera');
        const takeBtn = document.getElementById('take_snapshot');
        const retakeBtn = document.getElementById('retake_snapshot');
        const confirmBtn = document.getElementById('confirm_snapshot');
        const challengeBanner = document.getElementById('challenge_banner');
        const challengeText = document.getElementById('challenge_text');
        const faceGuide = document.getElementById('face_guide');

        // CHALLENGE GENERATOR
        const challenges = [
            "Peace Sign ✌️",
            "Thumbs Up 👍",
            "Touch Your Ear 👂",
            "Cover One Eye 👁️",
            "Open Mouth 😮",
            "Hand on Head 🙆",
            "Look Left ⬅️",
            "Look Right ➡️"
        ];

        function startChallenge() {
            const randomIndex = Math.floor(Math.random() * challenges.length);
            currentChallenge = challenges[randomIndex];
            challengeText.textContent = currentChallenge;
            challengeBanner.classList.remove('hidden');
        }

        startBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
                startBtn.classList.add('hidden');
                takeBtn.classList.remove('hidden');
                faceGuide.classList.remove('hidden');
                
                // Start Random Challenge
                startChallenge();

            } catch (err) {
                Swal.fire({icon:'error', title:'Camera Error', text:'Unable to access camera. Please allow permissions.'});
            }
        });

        takeBtn.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const ctx = canvas.getContext('2d');
            ctx.translate(canvas.width, 0); // Flip horizontally
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            selfieDataUrl = canvas.toDataURL('image/jpeg', 0.8);
            video.classList.add('hidden');
            faceGuide.classList.add('hidden');
            canvas.classList.remove('hidden');
            takeBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            confirmBtn.classList.remove('hidden');
        });

        retakeBtn.addEventListener('click', () => {
            canvas.classList.add('hidden');
            video.classList.remove('hidden');
            faceGuide.classList.remove('hidden');
            retakeBtn.classList.add('hidden');
            confirmBtn.classList.add('hidden');
            takeBtn.classList.remove('hidden');
        });

        confirmBtn.addEventListener('click', () => {
            console.log('[SELFIE] Confirm button clicked');
            
            if(!selfieDataUrl) {
                Swal.fire({icon:'warning', title:'No Image', text:'Please take a photo first!'});
                return;
            }

            console.log('[SELFIE] Image data length:', selfieDataUrl.length);
            console.log('[SELFIE] Challenge:', currentChallenge);

            // Disable button immediately to prevent double-click / show feedback
            confirmBtn.disabled = true;
            confirmBtn.innerText = "Uploading...";
            
            Swal.fire({title: 'Uploading Selfie...', didOpen: () => Swal.showLoading(), allowOutsideClick: false});

            console.log('[SELFIE] Starting fetch request...');

            // Setup Timeout (30s)
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000);

            fetch("{{ route('onboarding.community.upload_selfie') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ 
                    selfie_image: selfieDataUrl,
                    verification_note: currentChallenge 
                }),
                signal: controller.signal
            })
            .then(async res => {
                console.log('[SELFIE] Response received. Status:', res.status);
                clearTimeout(timeoutId);
                const contentType = res.headers.get("content-type");
                console.log('[SELFIE] Content-Type:', contentType);
                
                if (!res.ok) {
                    if(res.status === 413) throw new Error("Image too large. Please retry.");
                    const txt = await res.text();
                    console.error("Server Error Response:", txt);
                    throw new Error(`Server Error (${res.status})`);
                }

                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return res.json();
                } else {
                    const txt = await res.text();
                    console.error("Invalid Response:", txt);
                    throw new Error("Invalid server response. Check console.");
                }
            })
            .then(data => {
                console.log('[SELFIE] JSON parsed successfully:', data);
                if(data.success){
                    Swal.fire({icon:'success', title:'Verified!', text:'Selfie with gesture uploaded.', timer: 1500, showConfirmButton: false}).then(() => {
                        window.location.reload(); 
                    });
                } else {
                    throw new Error(data.message || 'Unknown server error');
                }
            })
            .catch(err => {
                console.error('[SELFIE] Error caught:', err);
                console.error('[SELFIE] Error name:', err.name);
                console.error('[SELFIE] Error message:', err.message);
                confirmBtn.disabled = false;
                confirmBtn.innerText = "Confirm & Upload";
                Swal.fire({
                    icon:'error', 
                    title:'Upload Failed', 
                    text: err.message,
                    footer: '<small>Common fix: Try taking the photo in better lighting or restart browser.</small>'
                });
            });
        });
    </script>
    @endpush
</x-guest-layout>