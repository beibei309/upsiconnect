<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="py-12 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Service</h1>
                    <p class="text-gray-500 mt-1">Update your service details</p>
                </div>
                <a href="{{ route('services.manage') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Manage</span>
                </a>
            </div>

            <form id="serviceForm" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title & Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ $service->title }}" required
                               class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" required
                                class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Image Upload -->
                @php
                    $currentImage = $service->image_path
                        ? (Str::startsWith($service->image_path, 'services/') ? asset('storage/'.$service->image_path) : asset($service->image_path))
                        : asset('images/default_service.jpg');
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <div class="mb-3 w-48">
                        <img id="imagePreview" src="{{ $currentImage }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm mb-2">
                    </div>
                    <input type="file" name="image" id="imageInput" class="w-full text-gray-700 px-3 py-2 border rounded-xl cursor-pointer hover:bg-gray-50">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Description</label>
                    <textarea name="description" rows="5"
                              class="w-full px-4 py-3 border rounded-xl">{{ $service->description }}</textarea>
                </div>

                <!-- Pricing Packages -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Pricing Packages</h3>

                    @php
                        $packages = [
                            ['name'=>'Basic','duration'=>$service->basic_duration,'frequency'=>$service->basic_frequency,'price'=>$service->basic_price,'description'=>$service->basic_description],
                            ['name'=>'Standard','duration'=>$service->standard_duration,'frequency'=>$service->standard_frequency,'price'=>$service->standard_price,'description'=>$service->standard_description],
                            ['name'=>'Premium','duration'=>$service->premium_duration,'frequency'=>$service->premium_frequency,'price'=>$service->premium_price,'description'=>$service->premium_description],
                        ];
                        $offerPackages = $service->standard_price || $service->premium_price;
                    @endphp

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="togglePackages" name="offer_packages" class="mr-2" {{ $offerPackages ? 'checked' : '' }}>
                        <label for="togglePackages" class="text-gray-700 font-medium cursor-pointer">Offer Standard & Premium Packages?</label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($packages as $i => $package)
                            <div class="package-card bg-gray-50 border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition
                                        {{ $i > 0 ? 'extra-package' : '' }}"
                                 style="{{ ($i > 0 && !$offerPackages) ? 'display:none;' : '' }}">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">{{ $package['name'] }} Package</h4>
                                <div class="space-y-2">
                                    <input type="text" name="packages[{{ $i }}][duration]" placeholder="Duration"
                                        value="{{ $package['duration'] }}"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400">
                                    <input type="text" name="packages[{{ $i }}][frequency]" placeholder="Frequency"
                                        value="{{ $package['frequency'] }}"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400">
                                    <input type="number" name="packages[{{ $i }}][price]" placeholder="Price"
                                        value="{{ $package['price'] }}" step="0.01"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400">
                                    <input type="text" name="packages[{{ $i }}][description]" placeholder="Description"
                                        value="{{ $package['description'] }}"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <script>
                        const toggle = document.getElementById('togglePackages');
                        const extraPackages = document.querySelectorAll('.extra-package');

                        function updatePackageVisibility() {
                            extraPackages.forEach(pkg => {
                                const inputs = pkg.querySelectorAll('input');
                                if (toggle.checked) {
                                    pkg.style.display = 'block';
                                    inputs.forEach(input => input.disabled = false);
                                } else {
                                    pkg.style.display = 'none';
                                    inputs.forEach(input => input.disabled = true);
                                }
                            });
                        }

                        toggle.addEventListener('change', updatePackageVisibility);
                        updatePackageVisibility(); // initialize on page load
                    </script>
                </div>

                <!-- Unavailable Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unavailable Dates</label>
                    <input type="text" id="unavailableDates" name="unavailable_dates"
                           value="{{ implode(',', json_decode($service->unavailable_dates ?? '[]', true)) }}"
                           class="w-full px-4 py-3 border rounded-xl">
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-indigo-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        imageInput.addEventListener('change', function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = e => imagePreview.src = e.target.result;
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "{{ $currentImage }}";
            }
        });

        // Flatpickr for unavailable dates
        flatpickr("#unavailableDates", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });

        // Submit form via AJAX
        document.getElementById('serviceForm').addEventListener('submit', function(e){
            e.preventDefault();

            // Enable disabled inputs so they are sent
            document.querySelectorAll('input:disabled').forEach(i => i.disabled = false);

            const formData = new FormData(this);
            formData.append('_method','PUT');

            fetch("{{ route('services.update',$service->id) }}", {
                method:'POST',
                body: formData,
                headers:{'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
            })
            .then(async res => {
                const text = await res.text();
                try {
                    const data = JSON.parse(text);
                    if(data.success){
                        alert(data.message);
                        window.location.href="{{ route('services.manage') }}";
                    } else {
                        alert('Error: ' + (data.error || 'Unknown'));
                    }
                } catch(e){
                    console.error('Invalid JSON', text);
                    alert('Server error. Check console.');
                }
            })
            .catch(err => console.error(err));
        });
    </script>
</x-app-layout>
