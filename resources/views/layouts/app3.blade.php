<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','PanenHub')</title>
    {{-- Tailwind CSS and Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Custom component styles --}}
    <style type="text/tailwindcss">
        @layer components {
      .hamburger-line {
        @apply w-6 h-0.5 bg-white rounded-full transition-all duration-300 origin-center;
      }
      .sidebar-link {
        @apply px-6 py-4 text-gray-200 hover:bg-green-950 hover:text-white
               transition-colors duration-200 border-l-4 border-transparent
               hover:border-green-950;
      }
      .sidebar-link.active {
        @apply bg-green-950 border-green-950;
      }
    }
  </style>
    @stack('head') {{-- additional head tags --}}
</head>

<body class="bg-gray-100 transition-all duration-300">

    {{-- Navbar --}}
    <nav class="bg-green-950 text-white p-4 shadow-md fixed w-full z-50">
        <div class="flex items-center pl-4">
            <button id="hamburger"
                class="flex flex-col justify-between h-6 w-6 focus:outline-none mr-4 group">
                <span class="hamburger-line group-[.active]:translate-y-[5.8px] group-[.active]:rotate-[23deg]"></span>
                <span class="hamburger-line group-[.active]:opacity-0"></span>
                <span class="hamburger-line group-[.active]:-translate-y-[5.8px] group-[.active]:-rotate-[23deg]"></span>
            </button>
            <div class="text-3xl font-bold">@yield('brand','PanenHub')</div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <div id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-green-950 shadow-lg z-40
              transform -translate-x-full transition-transform duration-300 ease-in-out pt-16">
        <nav class="flex flex-col">
            <a href="{{ route('buyer.dashboard') }}" class="sidebar-link">
                Dashboard
            </a>
            <a href="{{ route('buyer.katalog') }}" class="sidebar-link">
                Katalog
            </a>
            <a href="{{ route('buyer.orders.index') }}" class="sidebar-link">
                Pesanan Saya
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="w-full text-left sidebar-link">
                    Logout
                </button>
            </form>
        </nav>
    </div>

    {{-- Overlay --}}
    <div id="overlay"
        class="fixed inset-0 bg-black/50 z-30 opacity-0 invisible
              transition-opacity duration-300"></div>

    {{-- Main Content --}}
    <main id="content" class="pt-16 pl-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto p-4">
            @yield('content')
        </div>
    </main>

    {{-- Sidebar toggle script --}}
    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('translate-x-0');
            overlay.classList.toggle('opacity-100');
            overlay.classList.toggle('visible');
            overlay.classList.toggle('invisible');
        });

        overlay.addEventListener('click', () => {
            hamburger.classList.remove('active');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.remove('opacity-100', 'visible');
            overlay.classList.add('invisible');
        });
    </script>

    @stack('scripts') {{-- additional page scripts --}}
</body>

</html>