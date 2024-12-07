<!-- Footer Section -->
<footer class="{{ Auth::guard('admin')->check() ? 'bg-ultraViolet' : 'bg-wisteria' }} flex items-center justify-center text-center font-bold font-montserrat text-surface/75 h-16">
    <p class="text-white">&copy; Lend-A-Hand 2024</p>
</footer>