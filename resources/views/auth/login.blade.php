<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login PanenHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen p-4" style="background-image: url('https://montgomeryparks.org/wp-content/uploads/2022/09/Friends-of-the-Agricultural-History-Farm-Park.jpg'); background-size: cover; background-position: center;">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-green-900 px-6 py-4 border-b">
                <h4 class="text-center text-2xl font-semibold text-white">Login PanenHub</h4>
            </div>
            <div class="p-6">
                {{-- Error Messages --}}
                <div class="mb-4">
                    @if($errors->any())
                    <div class="bg-red-100 text-red-800 p-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="you@example.com" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="••••••••" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" />
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button
                            type="submit"
                            class="w-full py-2 px-4 bg-green-900 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>