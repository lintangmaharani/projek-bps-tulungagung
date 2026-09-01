<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa - BPS Tulungagung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bpsBlue: '#00428C',
                        bpsOrange: '#F38118',
                        bpsDark: '#002C61',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full p-8">
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Registrasi Mahasiswa</h2>
            <div class="w-12 h-1 bg-bpsOrange mx-auto mt-1 mb-2"></div>
            <p class="text-xs text-gray-500">Buat akun untuk mengajukan PKL/Magang di BPS Tulungagung</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 text-xs rounded-lg border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh : Bintang Timora" class="w-full px-3 py-2 text-sm border rounded-xl focus:ring-2 focus:ring-bpsBlue focus:outline-none border-gray-300">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email Aktif</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh : timora@univ.ac.id" class="w-full px-3 py-2 text-sm border rounded-xl focus:ring-2 focus:ring-bpsBlue focus:outline-none border-gray-300">
            </div>

            <!-- Password Utama -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="regPassword" name="password" required placeholder="Minimal 6 karakter" class="w-full pl-3 pr-10 py-2 text-sm border rounded-xl focus:ring-2 focus:ring-bpsBlue focus:outline-none border-gray-300">
                    <button type="button" onclick="togglePassword('regPassword')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" id="regConfirmPassword" name="password_confirmation" required placeholder="Ulangi password" class="w-full pl-3 pr-10 py-2 text-sm border rounded-xl focus:ring-2 focus:ring-bpsBlue focus:outline-none border-gray-300">
                    <button type="button" onclick="togglePassword('regConfirmPassword')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-bpsOrange hover:bg-orange-600 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md transition duration-200 text-sm">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-bpsBlue font-bold hover:underline">Login di sini &rarr;</a>
        </div>

    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>

</body>
</html>