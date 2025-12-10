<x-guest-layout>
    <div x-data="{ 
            selected: null, 
            toggle(id) { 
                this.selected = this.selected === id ? null : id 
            } 
        }" 
        class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans text-slate-800">

        <div class="max-w-3xl mx-auto">

            <div class="text-center mb-12">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-xs">Support Center</span>
                <h1 class="mt-2 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    How can we help you?
                </h1>
                <p class="mt-4 text-lg text-slate-500 max-w-xl mx-auto">
                    Everything you need to know about using S2U as a student or a community member.
                </p>
            </div>

            <div class="space-y-4">

                <div class="mb-2 ml-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">General & Accounts</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(1)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">Who can use S2U?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 1 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 1" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            S2U is designed for the UPSI ecosystem. It can be used by <strong>UPSI students</strong> (as service providers or buyers), <strong>UPSI staff</strong>, and the surrounding <strong>local community members</strong> who need ad-hoc services.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(2)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">Is S2U free to use?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 2 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 2" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            Yes. S2U is completely free to join and browse. There are <strong>no hidden platform fees or commissions</strong> charged by S2U. You pay the student directly for the service agreed upon.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(3)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">How do I create an account?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 3 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 3" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            Simply click the "Register" button, enter your email and create a password. If you are a student wanting to offer services, you will need to complete your profile and verify your UPSI student status in the dashboard.
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-2 ml-2 pt-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Services & Requests</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(4)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">What types of services can students offer?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 4 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 4" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            The sky is the limit! Common services include <strong>academic tutoring, graphic design, photography, videography, laptop repair/formatting, translation, running errands, and cleaning</strong>. As long as it adheres to university guidelines, it can be offered.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(5)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">How do I request a service?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 5 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 5" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            Browse the service listings using the search bar or categories. Once you find a provider you like, click "View Details" and use the <strong>"Contact" or "Request Service"</strong> button to discuss your needs directly.
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-2 ml-2 pt-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Safety & Support</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(6)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">Why was my service banned?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 6 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 6" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            We prioritize safety. Services are banned if they violate UPSI rules, contain inappropriate content, receive repeated reports from users, or involve unsafe illegal activities. Please review our Community Guidelines.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="toggle(7)" class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                        <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">What should I do if I face a problem?</span>
                        <span class="ml-6 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                 :class="selected === 7 ? 'rotate-180 text-indigo-600' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </button>
                    <div x-show="selected === 7" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                        <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                            If you encounter issues with a user or technical problems, please contact our support team immediately via the email below or use the "Report" function on the user's profile.
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-12 bg-indigo-50 border border-indigo-100 rounded-3xl p-8 text-center">
                <div class="mx-auto w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Still have questions?</h3>
                <p class="mt-2 text-gray-600 mb-6">Can’t find the answer you’re looking for? Please chat with our friendly team.</p>
                <a href="mailto:support@s2u.upsi.edu.my" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                    Contact Support
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>