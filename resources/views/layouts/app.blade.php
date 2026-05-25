<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex h-screen font-sans">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-gray-100">
                <div class="mr-3">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 0C7.16344 0 0 7.16344 0 16C0 24.8366 7.16344 32 16 32V0Z" fill="#1F2937" />
                        <path d="M32 16C32 7.16344 24.8366 0 16 0V9.6C19.5346 9.6 22.4 12.4654 22.4 16C22.4 19.5346 19.5346 22.4 16 22.4V32C24.8366 32 32 24.8366 32 16Z" fill="#4B5563" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-800">ERP</span>
                <div class="ml-auto text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
            </div>

            <nav class="p-4 space-y-1 mt-2">
                <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                    <svg class="w-5 h-5 mr-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Users</span>
                </a>

                <a href="/customers" class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->is('customers*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="font-medium">Customers</span>
                </a>

                <a href="{{ url('/services') }}" class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->is('services*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Services</span>
                </a>

                <a href="{{ url('/subscriptions') }}" class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->is('subscriptions*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Subscription</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100 mb-4">
            <button class="flex items-center w-full px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                <svg class="w-5 h-5 mr-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Sign Out</span>
            </button>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>