<nav class="bg-blue-500 p-4 flex justify-between items-center text-white">
    <div class="text-xl font-bold flex items-center gap-2">
        <span class="material-icons">dashboard</span>
        لوحة التحكم
    </div>
    {{-- <form method="POST" action="{{ route('logout') }}"> --}}
        {{-- @csrf --}}
        <button type="submit" class="flex items-center gap-2 bg-red-600 px-4 py-2 rounded hover:bg-red-700">
            <span class="material-icons">logout</span> تسجيل الخروج
        </button>
    {{-- </form> --}}
</nav>
