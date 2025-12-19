<x-guest-layout>
    <div x-data="{ 
            selected: null, 
            toggle(id) { 
                this.selected = this.selected === id ? null : id 
            } 
        }" 
        class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans text-slate-800">

        <div class="max-w-3xl mx-auto">

            {{-- HEADER --}}
            <div class="text-center mb-12">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-xs">Support Center</span>
                <h1 class="mt-2 text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    How can we help you?
                </h1>
                <p class="mt-4 text-lg text-slate-500 max-w-xl mx-auto">
                    Everything you need to know about using S2U as a student or a community member.
                </p>
            </div>

            {{-- DYNAMIC FAQ LOOP --}}
            @foreach ($faqs as $category => $items)
                
                {{-- Category Title --}}
                <div class="mb-2 ml-2 mt-8">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        {{ $category }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach ($items as $faq)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                            
                            {{-- Question Button --}}
                            <button @click="toggle({{ $faq->id }})" 
                                    class="w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none group">
                                <span class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                    {{ $faq->question }}
                                </span>
                                <span class="ml-6 flex-shrink-0">
                                    {{-- Icon Rotation Logic --}}
                                    <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300" 
                                         :class="selected === {{ $faq->id }} ? 'rotate-180 text-indigo-600' : ''" 
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            {{-- Answer Content --}}
                            <div x-show="selected === {{ $faq->id }}" 
                                 x-collapse 
                                 class="border-t border-slate-100 bg-slate-50/50">
                                <div class="px-6 py-4 text-slate-600 leading-relaxed text-sm">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endforeach

            {{-- CONTACT FOOTER --}}
            <div class="mt-12 bg-indigo-50 border border-indigo-100 rounded-3xl p-8 text-center">
                <div class="mx-auto w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Still have questions?</h3>
                <p class="mt-2 text-gray-600 mb-6">Can’t find the answer you’re looking for? Please chat with our friendly team.</p>
                <a href="mailto:support@s2u.upsi.edu.my" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                    Contact Support
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>