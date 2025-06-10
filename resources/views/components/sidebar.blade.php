{{-- resources/views/components/sidebar.blade.php --}}
<aside class="w-56 bg-gray-800 text-white p-4 shadow-md rounded-r-lg flex flex-col">
    <div class="text-xl font-bold mb-6 text-indigo-400">Admin Panel</div>
    <nav class="flex-1">
        <ul>
            <li class="mb-3">
                <a href="/dashboard" class="block p-2 rounded hover:bg-gray-700 transition duration-150">Dashboard</a>
            </li>
            <li class="mb-3">
                <a href="#" class="block p-2 rounded hover:bg-gray-700 transition duration-150">Pengaturan</a>
            </li>
            {{-- Tambahkan item menu sidebar lainnya di sini --}}
        </ul>
    </nav>
    
    <!-- Logout Form di Bawah Sidebar -->
    <div class="mt-auto pt-4 border-t border-gray-700">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left p-2 rounded bg-red-600 hover:bg-red-700 transition duration-150 text-white font-medium">
                Logout
            </button>
        </form>
    </div>
</aside>
