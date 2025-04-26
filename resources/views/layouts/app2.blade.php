<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title','PanenHub')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Custom components --}}
    <style type="text/tailwindcss">
        @layer components {
    .hamburger-line {
      @apply w-6 h-0.5 bg-white rounded-full transition-all duration-300 origin-center;
    }
    .sidebar-link {
      @apply px-6 py-4 text-gray-200 hover:bg-slate-700 hover:text-white
             transition-colors duration-200 border-l-4 border-transparent
             hover:border-blue-500;
    }
    .sidebar-link.active {
      @apply bg-slate-700 border-blue-500;
    }
  }
  </style>

    @stack('head')
</head>

<body class="bg-gray-100 transition-all duration-300">

    {{-- Navbar --}}
    <nav class="bg-slate-800 text-white p-4 shadow-md fixed w-full z-50">
        <div class="flex items-center">
            <button id="hamburger"
                class="flex flex-col justify-between h-6 w-6 focus:outline-none mr-4 group">
                <span class="hamburger-line group-[.active]:translate-y-[5.8px] group-[.active]:rotate-[23deg]"></span>
                <span class="hamburger-line group-[.active]:opacity-0"></span>
                <span class="hamburger-line group-[.active]:-translate-y-[5.8px] group-[.active]:-rotate-[23deg]"></span>
            </button>
            <div class="text-2xl font-bold">@yield('brand','PanenHub')</div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <div id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-slate-900 shadow-lg z-40
              transform -translate-x-full transition-transform duration-300 ease-in-out pt-16 flex flex-col">
        <a href="{{ route('koperasi.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('koperasi.farmers.index') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.farmers.*') ? 'active' : '' }}">
            Kelola Petani
        </a>
        <a href="{{ route('koperasi.stocks.index') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.stocks.*') ? 'active' : '' }}">
            Kelola Stok
        </a>
        <a href="{{ route('koperasi.orders.index') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.orders.*') ? 'active' : '' }}">
            Kelola Pesanan
        </a>
        <a href="{{ route('koperasi.reports.harvest') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.reports.harvest') ? 'active' : '' }}">
            Laporan Panen
        </a>
        <a href="{{ route('koperasi.reports.finance') }}"
            class="sidebar-link {{ request()->routeIs('koperasi.reports.finance') ? 'active' : '' }}">
            Laporan Keuangan
        </a>

        <div class="mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left sidebar-link">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Overlay (untuk klik di luar menutup sidebar) --}}
    <div id="overlay"
        class="fixed inset-0 bg-black/50 z-30 opacity-0 invisible
              transition-opacity duration-300"></div>

    {{-- Main Content Area --}}
    <main id="content" class="pt-16 transition-all duration-300">
        <div class="lg:pl-64">
            <div class="max-w-7xl mx-auto p-4">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Toggle Script --}}
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

    @stack('scripts')
</body>

</html>