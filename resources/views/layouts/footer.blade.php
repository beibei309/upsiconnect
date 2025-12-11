<footer class="bg-slate-900 text-white pt-16 pb-8 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <div class="col-span-1 lg:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <h3 class="text-3xl font-extrabold tracking-tight text-white">S2U</h3>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Connecting UPSI students with the community. Empowering talent, facilitating services, and building a trustworthy ecosystem for everyone.
                </p>
                
                <div class="flex space-x-4">
                    <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>

            <div class="hidden lg:block"></div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-200 mb-6">Explore</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('services.index') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Find Services</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Dashboard</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">My Profile</a></li>
                    @else
                        <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Sign Up</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Log In</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-200 mb-6">Support & Help</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('help') }}" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Help Center</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Contact Support</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Community Guidelines</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition-colors text-sm">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 my-8"></div>

        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} S2U (Student to Community). All rights reserved.</p>
            <p class="mt-2 md:mt-0 flex items-center">
                Built with <span class="text-red-500 mx-1">❤</span> for UPSI Students.
            </p>
        </div>
    </div>
</footer>