<x-guest-layout>

        <!-- Hero -->
        <section class="relative overflow-hidden bg-cover bg-center" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/6/69/Mosque_at_Shrine_of_Fariduddin_Ganjshakar_1.jpg');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center text-white">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                    Complaint Management System
                    <span class="block text-primary-200">District Pakpattan</span>
                </h1>
                <p class="mt-4 text-lg text-gray-200">Submit service complaints in minutes, track progress in real-time, and help us improve public services across Pakpattan.</p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ url('complaint') }}" class="px-5 py-3 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700">Register Complaint</a>
                <a href="#track" class="px-5 py-3 rounded-xl border border-white/30 hover:bg-white/10">Track Complaint</a>
                </div>
                <div class="mt-6 flex items-center gap-6 text-sm text-gray-200">
                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-green-400"></span>24/7 Online</div>
                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-400"></span>SMS & Email updates</div>
                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Urdu & English</div>
                </div>
            </div>
            <div class="relative">
                <div class="relative rounded-3xl border border-gray-200 shadow-sm bg-white p-4 text-gray-800">
                <div class="grid gap-4">
                    <div class="rounded-2xl bg-primary-600 text-white p-6">
                    <div class="text-sm opacity-90">Active Complaints</div>
                    <div class="mt-1 text-3xl font-bold">{{ number_format($summary->total) }}</div>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                        <div><div class="text-xl font-bold">{{ number_format($summary->resolved) }}</div><div class="text-xs opacity-90">Resolved</div></div>
                        <div><div class="text-xl font-bold">{{ number_format($summary->resolved) }}</div><div class="text-xs opacity-90">Pending</div></div>
                        <div><div class="text-xl font-bold">{{ number_format($summary->overdue) }}</div><div class="text-xs opacity-90">Overdue</div></div>
                    </div>
                    </div>
                    {{-- <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="text-sm font-semibold">Recent Updates</div>
                        <ul class="mt-2 space-y-2 text-sm text-gray-600">
                            <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-green-500"></span> Complaint <span class="font-semibold">PKP-2025-1032</span> resolved</li>
                            <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Complaint <span class="font-semibold">PKP-2025-0975</span> assigned</li>
                            <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span> New complaint <span class="font-semibold">PKP-2025-1044</span> submitted</li>
                        </ul>
                    </div> --}}
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="border-y border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-6 rounded-2xl bg-gray-50">
                <div class="text-3xl font-extrabold text-gray-900">2m+</div>
                <div class="text-sm text-gray-600">Residents Served</div>
            </div>
            <div class="p-6 rounded-2xl bg-gray-50">
                <div class="text-3xl font-extrabold text-gray-900">10k+</div>
                <div class="text-sm text-gray-600">Complaints / year</div>
            </div>
            <div class="p-6 rounded-2xl bg-gray-50">
                <div class="text-3xl font-extrabold text-gray-900">92%</div>
                <div class="text-sm text-gray-600">On-time Resolution</div>
            </div>
            <div class="p-6 rounded-2xl bg-gray-50">
                <div class="text-3xl font-extrabold text-gray-900">24/7</div>
                <div class="text-sm text-gray-600">Online Support</div>
            </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="max-w-7xl mx-auto px-4 py-16">
            <div class="max-w-2xl">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Built for citizens, trusted by administration</h2>
            <p class="mt-3 text-gray-600">A simple, transparent and efficient way to address civic issues in District Pakpattan.</p>
            </div>

            <div class="mt-10 grid md:grid-cols-3 gap-6">
            <!-- Feature Card -->
            <div class="p-6 rounded-2xl border border-gray-200">
                <div class="h-11 w-11 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                <!-- Shield Check icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5.25-3.438 9.438-8 10-4.562-.562-8-4.75-8-10V7l8-4z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold">Transparent Tracking</h3>
                <p class="mt-1 text-sm text-gray-600">Get SMS/email updates at every step — submission, assignment, action and resolution.</p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-200">
                <div class="h-11 w-11 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                <!-- Lightning bolt icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold">Fast Escalations</h3>
                <p class="mt-1 text-sm text-gray-600">Auto-escalation to authorities if SLAs are missed, ensuring timely action.</p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-200">
                <div class="h-11 w-11 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                <!-- Globe icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9-9 4.03-9 9 4.03 9 9 9zm0 0c2.5-2.63 4-5.5 4-9s-1.5-6.37-4-9m0 18c-2.5-2.63-4-5.5-4-9s1.5-6.37 4-9"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold">Urdu + English</h3>
                <p class="mt-1 text-sm text-gray-600">Use the system comfortably in your preferred language, with accessibility in mind.</p>
            </div>
            </div>
        </section>

        <!-- How it works -->
        <section id="how" class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 py-16">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">How it works</h2>
            <div class="mt-8 grid md:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-white border border-gray-200">
                <div class="text-xs font-semibold text-primary-700">Step 1</div>
                <h3 class="mt-1 font-semibold">Submit</h3>
                <p class="mt-1 text-sm text-gray-600">Describe your issue, add photos/location and submit online.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white border border-gray-200">
                <div class="text-xs font-semibold text-primary-700">Step 2</div>
                <h3 class="mt-1 font-semibold">Assign</h3>
                <p class="mt-1 text-sm text-gray-600">Complaint is assigned to the relevant department automatically.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white border border-gray-200">
                <div class="text-xs font-semibold text-primary-700">Step 3</div>
                <h3 class="mt-1 font-semibold">Action</h3>
                <p class="mt-1 text-sm text-gray-600">Department takes action and posts updates with evidence.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white border border-gray-200">
                <div class="text-xs font-semibold text-primary-700">Step 4</div>
                <h3 class="mt-1 font-semibold">Resolve</h3>
                <p class="mt-1 text-sm text-gray-600">Citizen confirms resolution; case is closed.</p>
                </div>
            </div>
            </div>
        </section>

        <!-- Categories -->
        <section id="categories" class="max-w-7xl mx-auto px-4 py-16">
            <div class="flex items-end justify-between gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Popular categories</h2>
                <p class="mt-2 text-gray-600">Choose the most relevant category for faster routing.</p>
            </div>
            <a href="#register-complaint" class="hidden md:inline-flex px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">See all</a>
            </div>

            <div class="mt-8 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Category card template -->
            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Road icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2 6m-2-6l-2 6m2-6v18m0-6l2 6m-2-6l-2 6"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Roads & Street Lights</div>
                    <div class="text-xs text-gray-600">Damaged roads, dark streets</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Trash icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2m-1 0l-1 14H9L8 6"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Solid Waste</div>
                    <div class="text-xs text-gray-600">Garbage, sanitation issues</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Hospital icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M6 21V9a3 3 0 013-3h6a3 3 0 013 3v12M9 12h6M12 9v6"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Health & Hospitals</div>
                    <div class="text-xs text-gray-600">Cleanliness, facilities</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Dog icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12l-2 3h3l2-3m6 0l2 3h3l-2-3m-8 3h6m-9 3h12"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Stray Dogs</div>
                    <div class="text-xs text-gray-600">Dog bite risk, safety</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Water icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c4 5 6 8 6 11a6 6 0 11-12 0c0-3 2-6 6-11z"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Water & Sanitation</div>
                    <div class="text-xs text-gray-600">Drainage, water supply</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Building icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21h16M6 21V8h12v13M9 8V3h6v5"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Encroachment</div>
                    <div class="text-xs text-gray-600">Illegal structures</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Gas cylinder icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M8 7a4 4 0 118 0M7 7v12a3 3 0 003 3h4a3 3 0 003-3V7"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Gas Decanting</div>
                    <div class="text-xs text-gray-600">Unsafe practices</div>
                </div>
                </div>
            </a>

            <a href="#register-complaint" class="group p-5 rounded-2xl border border-gray-200 hover:shadow-sm bg-white">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                    <!-- Spray can icon (wall chalking) -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M9 7V4h6v3M7 10h10v8a3 3 0 01-3 3H10a3 3 0 01-3-3v-8z"/></svg>
                </div>
                <div>
                    <div class="font-semibold">Wall Chalking</div>
                    <div class="text-xs text-gray-600">Defacement, posters</div>
                </div>
                </div>
            </a>
            </div>
        </section>

        <!-- Track Complaint -->
        <section id="track" class="bg-white">
            <div class="max-w-3xl mx-auto px-4 py-16 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Track your complaint</h2>
            <p class="mt-2 text-gray-600">Enter your complaint number to view the latest status and updates.</p>
            <form action="#" method="GET" class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <label for="complaint_no" class="sr-only">Complaint Number</label>
                <input id="complaint_no" name="complaint_no" type="text" placeholder="e.g. PKP-2025-1044" class="w-full sm:w-96 px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                <button type="submit" class="px-5 py-3 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700">Track</button>
            </form>
            {{-- <p class="mt-3 text-xs text-gray-500">Tip: You can also track by SMS. Send your number to <span class="font-semibold">8001</span>.</p> --}}
            </div>
        </section>

        <!-- Call to action -->
        <section id="register-complaint" class="relative1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-primary-50 to-white pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Have an issue to report?</h2>
                <p class="mt-2 text-gray-600">Help us keep Pakpattan safe and responsive. Submit a complaint now — it only takes a minute.</p>
            </div>
            <div class="flex md:justify-end">
                <a href="{{ url('complaint') }}" class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-semibold hover:bg-primary-700">Register Complaint</a>
            </div>
            </div>
        </section>

        <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        }
        // Current year
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</x-guest-layout>
