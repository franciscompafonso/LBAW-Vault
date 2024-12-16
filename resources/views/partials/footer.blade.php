<!-- Footer Section -->
<footer class="{{ Auth::guard('admin')->check() ? 'bg-ultraViolet' : 'bg-wisteria' }} flex items-center justify-center text-center font-bold font-montserrat text-surface/75 h-16 px-4">
    <p class="text-white">&copy; Lend-A-Hand 2024</p>

    <p class="text-white mx-4"> • </p>

    <a href="{{ url('/about') }}" class="text-whiteSmoke font-montserrat hover:text-darkSlateGrey mx-2" title="About Us">
        About Us
    </a>

    <p class="text-white mx-4"> • </p>

    <a href="{{ url('/faqs') }}" class="text-whiteSmoke font-montserrat hover:text-darkSlateGrey mx-2" title="Frequently Asked Questions">
        FAQs
    </a>
</footer>
