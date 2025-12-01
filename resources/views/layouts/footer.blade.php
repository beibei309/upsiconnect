        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-2xl font-bold mb-4">S2U</h3>
                        <p class="text-gray-400 mb-4">
                            Student to Community
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('search.index') }}" class="hover:text-white transition">Browse
                                    Services</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard</a>
                                </li>
                            @else
                                <li><a href="{{ route('register') }}" class="hover:text-white transition">Sign Up</a>
                                </li>
                                <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Help
                                    Center</a></li>
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Contact Us</a>
                            </li>
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Community
                                    Guidelines</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} UpsiConnect. Built with ❤️ for UPSI students.</p>
                </div>
            </div>
        </footer>