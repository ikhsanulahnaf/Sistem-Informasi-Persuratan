@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl card-shadow p-6" data-aos="zoom-in">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Tambah Pengguna Baru</h3>

            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Role</label>
                        <select name="role" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="departemen">Departemen</option>
                            <option value="rektor">Rektor</option>
                            <option value="wakil_rektor">Wakil Rektor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Unit / Bagian (opsional)</label>
                        <input type="text" name="unit"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Bidang Akademik">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('user.index') }}"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit"
                            class="btn-primary text-white px-5 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                            Tambah Pengguna
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection