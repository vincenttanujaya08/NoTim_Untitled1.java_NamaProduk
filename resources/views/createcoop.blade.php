<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Koperasi Baru</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 p-5">
            <h1 class="text-white text-2xl font-semibold">Tambah Koperasi Baru</h1>
        </div>

        <!-- Form Container -->
        <div class="p-6">
            <!-- Success Message -->
            <div id="successMsg" class="hidden mb-4 p-3 bg-green-100 text-green-800 rounded">
                <!-- Injected via server-side session('success') -->
                Berhasil menambahkan koperasi!
            </div>

            <!-- Error List -->
            <div id="errorMsg" class="hidden mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside text-sm">
                    <!-- Injected via server-side $errors -->
                    <li>Nama koperasi wajib diisi.</li>
                    <li>Alamat wajib diisi.</li>
                </ul>
            </div>

            <!-- Form -->
            <form
                action="{{ route('cooperatives.store') }}"
                method="POST"
                class="space-y-5">
                <!-- CSRF token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Koperasi
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan nama koperasi" />
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat
                    </label>
                    <textarea
                        name="address"
                        id="address"
                        rows="4"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a
                        href="/super/dashboard"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>