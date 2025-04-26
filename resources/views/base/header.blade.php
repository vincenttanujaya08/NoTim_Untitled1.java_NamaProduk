<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer components {
            .hamburger-line {
                @apply w-6 h-0.5 bg-white rounded-full transition-all duration-300 origin-center;
            }
            
            .sidebar-link {
                @apply px-6 py-4 text-gray-200 hover:bg-slate-700 hover:text-white transition-colors duration-200 border-l-4 border-transparent hover:border-blue-500;
            }
            
            .sidebar-link.active {
                @apply bg-slate-700 border-blue-500;
            }
        }
    </style>
</head>
<body class="bg-gray-100 transition-all duration-300">
    <!-- Navbar -->
    <nav class="bg-slate-800 text-white p-4 shadow-md fixed w-full z-50">
        <div class="flex items-center pl-4">
            <!-- Hamburger Button di Kiri -->
            <button id="hamburger" class="flex flex-col justify-between h-6 w-6 focus:outline-none mr-4 group">
                <span class="hamburger-line group-[.active]:translate-y-[11px] group-[.active]:rotate-[45deg]"></span>
                <span class="hamburger-line group-[.active]:opacity-0"></span>
                <span class="hamburger-line group-[.active]:-translate-y-[11px] group-[.active]:-rotate-[45deg]"></span>
            </button>
            
            <div class="text-xl font-bold">Panen Hub</div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-slate-900 shadow-lg z-40 transform -translate-x-full transition-transform duration-300 ease-in-out pt-16">
        <div class="grid-rows-4 w-64">
          <div class="hover:bg-blue-600 py-2">
            <a href="home" class="text-white ml-8">Home</a>
          </div>
          <div class="hover:bg-blue-600 py-2">
            <a href="katalog" class="text-white ml-8">Katalog</a>
          </div>
          <div class="hover:bg-blue-600 py-2">
            <a href="login" class="text-white ml-8">Login</a>
          </div>
          <div class="hover:bg-blue-600 py-2">
            <a href="signup" class="text-white ml-8">Sign Up</a>
          </div>
        </div>
      </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 opacity-0 invisible transition-opacity duration-300"></div>

    <!-- Main Content -->
    {{-- <main id="content" class="pt-16 px-4 min-h-screen transition-all duration-300">
        <div class="container mx-auto py-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-4">Welcome to My Website</h1>
            <p class="text-slate-600">Click the hamburger menu to see the diagonal cross animation.</p>
        </div>
    </main> --}}

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const content = document.getElementById('content');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('translate-x-0');
            overlay.classList.toggle('opacity-100');
            overlay.classList.toggle('visible');
            overlay.classList.toggle('invisible');
            
            // if (sidebar.classList.contains('translate-x-0')) {
            //     content.classList.add('ml-64');
            // } else {
            //     content.classList.remove('ml-64');
            // }
        });

        overlay.addEventListener('click', () => {
            hamburger.classList.remove('active');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.remove('opacity-100', 'visible');
            overlay.classList.add('invisible');
            content.classList.remove('ml-64');
        });

        // Close sidebar when clicking on a link
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.remove('opacity-100', 'visible');
                overlay.classList.add('invisible');
                content.classList.remove('ml-64');
                
                // Set active link
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    </script>
</body>
</html>