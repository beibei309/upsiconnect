<x-app-layout>
    <br><br>
    <div class="bg-gray-50 min-h-screen flex justify-center items-center py-10">
        <div class="max-w-3xl bg-white p-8 rounded-lg shadow-xl card-shadow w-full">
            <h1 class="text-4xl font-semibold text-gray-900 mb-6">Join as a Part-timer</h1>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Picture Section -->
                <!-- Profile Picture Section -->
                <div class="mb-6">
                    <label class="block text-lg font-medium text-gray-900 mb-2">Profile Picture</label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden relative">
                            <img id="profile-photo-preview"
                                src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                alt="Profile Photo" class="w-full h-full object-cover">
                            <label for="profile_photo_input"
                                class="absolute inset-0 bg-black bg-opacity-25 text-white flex items-center justify-center rounded-full cursor-pointer opacity-0 hover:opacity-100 transition-opacity">
                                Change
                            </label>
                        </div>
                        <input type="file" id="profile_photo_input" name="profile_photo" accept="image/*"
                            class="hidden" />
                    </div>
                    @error('profile_photo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    // Preview new selected profile photo
                    document.getElementById('profile_photo_input').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('profile-photo-preview').src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>



                <!-- About Section -->
                <div class="mb-6">
                    <label for="bio" class="block text-lg font-medium text-gray-900 mb-2">About</label>
                    <textarea id="bio" name="bio" rows="4"
                        class="block w-full p-4 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Write about yourself...">{{ old('bio', auth()->user()->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Skills and Expertise Section -->
                <div class="mb-6">
                    <label for="skills" class="block text-lg font-medium text-gray-900 mb-2">Skills and
                        Expertise</label>
                    <div class="flex flex-col">
                        <input type="text" name="skills"
                            class="form-control block w-full p-4 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-4"
                            placeholder="Enter skill (e.g., A-capella singing)"
                            value="{{ old('skills', auth()->user()->skills) }}" />
                        <select id="expertise_level" name="expertise_level[]"
                            class="block w-full p-4 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="beginner" {{ old('expertise_level.0') == 'beginner' ? 'selected' : '' }}>
                                Beginner</option>
                            <option value="intermediate"
                                {{ old('expertise_level.0') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="expert" {{ old('expertise_level.0') == 'expert' ? 'selected' : '' }}>Expert
                            </option>
                        </select>
                    </div>
                    @error('skills')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Work Experience Section -->
                <div class="mb-6">
                    <label for="work_experience" class="block text-lg font-medium text-gray-900 mb-2">Work Experience
                        (Optional)</label>

                    <!-- Textarea for message -->
                    <textarea id="work_experience_message" name="work_experience_message" rows="3"
                        class="block w-full border-2 border-gray-300 rounded-md p-2 text-gray-700"
                        placeholder="Contoh: 2 tahun kerja di restoran, part-time cashier, dll (optional)">{{ old('work_experience_message', auth()->user()->work_experience_message ?? '') }}</textarea>

                    <!-- File input -->
                    <input type="file" id="work_experience_file" name="work_experience_file"
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                        class="block w-full mt-3 text-sm text-gray-500 border-2 border-gray-300 rounded-md p-2" />

                    <p class="text-xs text-gray-500 mt-2">Acceptable file types: .pdf, .doc, .docx, .txt, .jpg, .jpeg,
                        .png</p>

                    @error('work_experience_message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('work_experience_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('profile.edit') }}"
                        class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">Back</a>
                    <button type="submit"
                        class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .card-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-shadow:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .focus\:ring-indigo-500:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
    </style>

    <script>
        // Preview profile photo
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-photo-preview').src = e.target.result;
            }
            if (file) reader.readAsDataURL(file);
        });
    </script>

    @if (session('ready_to_help'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Ready to help people!',
                    icon: 'success',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        // Redirect to services.manage after 5 seconds
                        window.location.href = "{{ route('services.create') }}";
                    }
                });
            });
        </script>
    @endif

</x-app-layout>
