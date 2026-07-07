<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Persuratan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
        }

        .login-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4" style="background-image: url('images/foto iti.jpeg'); background-size: cover;">
    <div class="w-full max-w-md bg-white rounded-2xl login-card p-8">
        <div class="text-center mb-10" data-aos="fade-down">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-400 text-blue-900 mb-4">
                <img src="images/logo-iti.png" alt="">
            </div>
            <h1 class="text-3xl font-bold text-black">Sistem Persuratan</h1>
            <p class="text-black mt-2">Masuk untuk mengelola surat Anda</p>
        </div>

        <div class="" data-aos="zoom-in" data-aos-delay="200">
            @if($errors->any())
                <div class="mb-5 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="contoh@iti.ac.id">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-[1.02] duration-200">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <p class="text-center text-black mt-6 text-sm">
            © {{ date('Y') }} Institut Teknologi Indonesia. All rights reserved.
        </p>
    </div>

    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>

</html>