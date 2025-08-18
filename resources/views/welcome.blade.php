<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DC Pakpattan — Complaint Management System</title>
  <meta name="description" content="Official Complaint Management System for DC Pakpattan. Submit, track, and resolve public service complaints." />

  <!-- Tailwind Play CDN for quick demo. In production, compile Tailwind via Laravel Mix/Vite. -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Optional: Tailwind config tweaks
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#effef6',
              100: '#d9fde8',
              200: '#b1f7ce',
              300: '#7aebb0',
              400: '#3ed593',
              500: '#16b176', // Primary brand green
              600: '#0d8f60',
              700: '#0c714f',
              800: '#0d5a40',
              900: '#0c4a36'
            }
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif; }
  </style>
</head>
<body class="bg-white text-gray-800">
  <!-- Top Bar -->
  <div class="bg-primary-600 text-white text-sm">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
      <p class="truncate">Government of the Punjab — District Pakpattan</p>
      <div class="flex items-center gap-6">
        <a href="#contact" class="hover:underline">Contact</a>
        <a href="tel:1122" class="font-semibold">Helpline: 1122</a>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-gray-100">
    <nav class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <a href="#" class="flex items-center gap-2">
          <!-- Placeholder Logo -->
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-100 text-primary-700 font-black">DC</span>
          <div class="leading-tight">
            <div class="text-sm font-semibold">Deputy Commissioner</div>
            <div class="text-xs text-gray-500 -mt-0.5">Pakpattan</div>
          </div>
        </a>

        <div class="hidden md:flex items-center gap-8">
          <a href="#features" class="hover:text-primary-700">Features</a>
          <a href="#how" class="hover:text-primary-700">How it works</a>
          <a href="#categories" class="hover:text-primary-700">Categories</a>
          <a href="#track" class="hover:text-primary-700">Track</a>
        </div>

        <div class="hidden md:flex items-center gap-3">
          <a href="{{ url('login') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Login</a>
          <a href="{{ url('complaint') }}" class="px-4 py-2 rounded-xl bg-primary-600 text-white hover:bg-primary-700">Register Complaint</a>
        </div>

        <!-- Mobile menu button -->
        <button id="menuBtn" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200">
          <span class="sr-only">Open menu</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        </button>
      </div>

      <!-- Mobile menu -->
      <div id="mobileMenu" class="md:hidden hidden border-t border-gray-100 py-3">
        <div class="flex flex-col gap-2">
          <a href="#features" class="px-2 py-2 rounded-lg hover:bg-gray-50">Features</a>
          <a href="#how" class="px-2 py-2 rounded-lg hover:bg-gray-50">How it works</a>
          <a href="#categories" class="px-2 py-2 rounded-lg hover:bg-gray-50">Categories</a>
          <a href="#track" class="px-2 py-2 rounded-lg hover:bg-gray-50">Track</a>
          <div class="h-px bg-gray-100 my-2"></div>
          <a href="#login" class="px-2 py-2 rounded-lg border border-gray-200 text-center">Login</a>
          <a href="#register-complaint" class="px-2 py-2 rounded-lg bg-primary-600 text-white text-center">Register Complaint</a>
        </div>
      </div>
    </nav>
  </header>

  <!-- Hero -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-primary-50 to-white pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
          Complaint Management System
          <span class="block text-primary-600">District Pakpattan</span>
        </h1>
        <p class="mt-4 text-lg text-gray-600">Submit service complaints in minutes, track progress in real-time, and help us improve public services across Pakpattan.</p>
        <div class="mt-8 flex flex-wrap items-center gap-3">
          <a href="#register-complaint" class="px-5 py-3 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700">Register Complaint</a>
          <a href="#track" class="px-5 py-3 rounded-xl border border-gray-200 hover:bg-gray-50">Track Complaint</a>
        </div>
        <div class="mt-6 flex items-center gap-6 text-sm text-gray-600">
          <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-green-500"></span>24/7 Online</div>
          <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span>SMS & Email updates</div>
          <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span>Urdu & English</div>
        </div>
      </div>
      <div class="relative">
        <div class="absolute -inset-6 bg-gradient-to-tr from-primary-100/60 to-transparent rounded-3xl blur-2xl"></div>
        <div class="relative rounded-3xl border border-gray-200 shadow-sm bg-white p-4">
          <!-- Mocked dashboard preview card -->
          <div class="grid gap-4">
            <div class="rounded-2xl bg-primary-600 text-white p-6">
              <div class="text-sm opacity-90">Active Complaints</div>
              <div class="mt-1 text-3xl font-bold">12,457</div>
              <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                <div><div class="text-xl font-bold">7,130</div><div class="text-xs opacity-90">Resolved</div></div>
                <div><div class="text-xl font-bold">3,800</div><div class="text-xs opacity-90">Pending</div></div>
                <div><div class="text-xl font-bold">1,527</div><div class="text-xs opacity-90">In Progress</div></div>
              </div>
            </div>
            <div class="rounded-2xl border border-gray-200 p-4">
              <div class="text-sm font-semibold">Recent Updates</div>
              <ul class="mt-2 space-y-2 text-sm text-gray-600">
                <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-green-500"></span> Complaint <span class="font-semibold">PKP-2025-1032</span> resolved</li>
                <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Complaint <span class="font-semibold">PKP-2025-0975</span> assigned</li>
                <li class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span> New complaint <span class="font-semibold">PKP-2025-1044</span> submitted</li>
              </ul>
            </div>
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
      <p class="mt-3 text-xs text-gray-500">Tip: You can also track by SMS. Send your number to <span class="font-semibold">8001</span>.</p>
    </div>
  </section>

  <!-- Call to action -->
  <section id="register-complaint" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-primary-50 to-white pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Have an issue to report?</h2>
        <p class="mt-2 text-gray-600">Help us keep Pakpattan safe and responsive. Submit a complaint now — it only takes a minute.</p>
      </div>
      <div class="flex md:justify-end">
        <a href="#" class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-semibold hover:bg-primary-700">Start Complaint</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="border-t border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
      <div class="md:col-span-2">
        <div class="flex items-center gap-2">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-100 text-primary-700 font-black">DC</span>
          <div class="leading-tight">
            <div class="text-sm font-semibold">Deputy Commissioner</div>
            <div class="text-xs text-gray-500 -mt-0.5">Pakpattan</div>
          </div>
        </div>
        <p class="mt-4 text-sm text-gray-600 max-w-lg">Official portal for handling public service complaints. Together, we can make Pakpattan better for everyone.</p>
      </div>

      <div>
        <h3 class="text-sm font-semibold">Quick Links</h3>
        <ul class="mt-3 space-y-2 text-sm text-gray-600">
          <li><a href="#features" class="hover:text-primary-700">Features</a></li>
          <li><a href="#how" class="hover:text-primary-700">How it works</a></li>
          <li><a href="#categories" class="hover:text-primary-700">Categories</a></li>
          <li><a href="#track" class="hover:text-primary-700">Track Complaint</a></li>
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-semibold">Contact</h3>
        <ul class="mt-3 space-y-2 text-sm text-gray-600">
          <li>DC Office, Pakpattan</li>
          <li>Mon–Fri, 9:00–5:00</li>
          <li><a href="tel:+92457300000" class="hover:text-primary-700">+92 (457) 300000</a></li>
          <li><a href="mailto:info@pakpattan.gov.pk" class="hover:text-primary-700">info@pakpattan.gov.pk</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-gray-100">
      <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>© <span id="year"></span> District Pakpattan — All rights reserved.</p>
        <p>Powered by CMS</p>
      </div>
    </div>
  </footer>

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
</body>
</html>
