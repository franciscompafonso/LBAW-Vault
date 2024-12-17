<!-- Footer Section -->
<footer class="{{ Auth::guard('admin')->check() ? 'bg-ultraViolet' : 'bg-wisteria' }} flex items-center justify-center text-center font-bold font-montserrat text-surface/75 h-16 px-4">
    <p class="text-white">&copy; Lend-A-Hand 2024</p>

    <p class="mx-4 text-white"> • </p>

    <a href="{{ url('/about') }}" class="mx-2 text-whiteSmoke font-montserrat hover:text-darkSlateGrey" title="About Us" aria-label="Learn more about us">
        About Us
    </a>

    <p class="mx-4 text-white"> • </p>

    <a href="{{ url('/faqs') }}" class="mx-2 text-whiteSmoke font-montserrat hover:text-darkSlateGrey" title="Frequently Asked Questions" aria-label="Visit the Frequently Asked Questions page">
        FAQs
    </a>
</footer>
