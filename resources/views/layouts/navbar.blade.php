<nav class="bg-blue-500 p-4 flex justify-between items-center text-white">
    <div class="text-xl font-bold flex items-center gap-2">
        <span class="material-icons">dashboard</span>
        لوحة التحكم
    </div>
    <button id="logout-btn" class="bg-red-500 text-white px-4 py-2 rounded">تسجيل الخروج</button>

</nav>

<script>
    document.getElementById('logout-btn').addEventListener('click', function() {
        fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(() => location.reload());
    });
</script>
