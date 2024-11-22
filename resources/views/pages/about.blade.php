@extends('layouts.app')

@section('content')
    <div class="container max-w-8xl  mx-auto p-8">
        <h1 class="text-4xl font-montserrat text-ultraViolet font-extrabold text-center mb-8">About Us</h1>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet ">Our Mission</h2>
            <p class="text-xl font-montserrat  text-eerieBlack mt-4">
                At Lend-A-Hand, we are committed to helping people connect with the right events and opportunities to improve their lives.
                Whether you're looking to volunteer, join an event, or make a difference in your community, we aim to be your go-to platform for all things community-driven.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Our Story</h2>
            <p class="text-xl font-montserrat text-eerieBlack mt-4">
                Founded in 2024, Lend-A-Hand was created with the goal of bridging the gap between people in need and those willing to lend a hand. We understand that meaningful connections are the foundation of any great community, and we’re here to facilitate those connections.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Our Team</h2>
            <div class="flex flex-wrap gap-8 mt-4">
                <div class="w-full md:w-1/5 text-center">
                <a href="https://github.com/progr-amador">
                    <img src="https://avatars.githubusercontent.com/u/132162311?v=4" alt="Team Member 1" class="rounded-full mx-auto mb-4 w-60 h-60">
                </a>
                    <h3 class="font-bold font-montserrat text-xl text-ultraViolet">Alexandre Ramos</h3>
                    
                </div>
                <div class="w-full md:w-1/5 text-center">
                    <a href="https://github.com/g3raldes">
                        <img src="https://avatars.githubusercontent.com/u/116494634?v=4" alt="Team Member 2" class="rounded-full mx-auto mb-4 w-60 h-60">
                    </a>
                    <h3 class="font-bold font-montserrat text-xl text-ultraViolet">Filipa Geraldes</h3>
                    
                </div>
                <div class="w-full md:w-1/5 text-center">
                    <a href="https://github.com/apilifogladif">
                        <img src="https://avatars.githubusercontent.com/u/109923498?v=4" alt="Team Member 3" class="rounded-full mx-auto mb-4 w-60 h-60">
                    </a>
                    <h3 class="font-bold font-montserrat text-xl text-ultraViolet">Filipa Fidalgo</h3>
                    
                </div>
                <div class="w-full md:w-1/5 text-center">
                    <a href="https://github.com/franciscoafonso04">
                        <img src="https://avatars.githubusercontent.com/u/132215846?s=96&v=4" alt="Team Member 3" class="rounded-full mx-auto mb-4 w-60 h-60">
                    </a>
                    <h3 class="font-bold font-montserrat text-xl text-ultraViolet">Francisco Afonso</h3>
                    
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Get In Touch</h2>
            <p class="text-xl font-montserrat text-eerieBlack mt-4">
                We would love to hear from you! If you have any questions, suggestions, or want to get involved, feel free to reach out.
            </p>
            <p class="mt-4 text-eerieBlack font-montserrat">
                Email us at <a href="mailto:info@lend-a-hand.com" class="text-blue-500">info@lend-a-hand.com</a>
            </p>
        </section>
    </div>
@endsection
