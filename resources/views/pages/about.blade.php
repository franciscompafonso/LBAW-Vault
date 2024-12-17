@extends('layouts.app')

@section('Title', 'About Us')
@section('content')
    <div class="container p-8 mx-auto max-w-8xl">
        <h1 class="mb-8 text-4xl font-extrabold text-center font-montserrat text-ultraViolet">About Us</h1>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet ">Our Mission</h2>
            <p class="mt-4 text-xl font-montserrat text-eerieBlack">
                At Lend-A-Hand, we are committed to helping people connect with the right events and opportunities to improve their lives.
                Whether you're looking to volunteer, join an event, or make a difference in your community, we aim to be your go-to platform for all things community-driven.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Our Story</h2>
            <p class="mt-4 text-xl font-montserrat text-eerieBlack">
                Founded in 2024, Lend-A-Hand was created with the goal of bridging the gap between people in need and those willing to lend a hand. We believe that meaningful connections are the foundation of any great community, and our mission is to make those connections easier and more accessible.
            </p>
            <p class="mt-4 text-xl font-montserrat text-eerieBlack">
                Lend-A-Hand was developed as part of the Database and Web Applications Laboratory course during the 3rd year of the Informatics and Computing Engineering program at FEUP (Faculty of Engineering, University of Porto).
            </p>
            <p class="mt-4 text-xl font-montserrat text-eerieBlack">
                This project is a reflection of our commitment to combining technical skills with a drive to make a positive impact on society, creating a platform that fosters collaboration and support.
            </p>

        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Our Team</h2>
            <div class="flex flex-wrap gap-8 mt-4">
                <div class="w-full text-center md:w-1/5">
                <a href="https://github.com/progr-amador" aria-label="Alexandre Ramos GitHub Profile">
                    <img src="https://avatars.githubusercontent.com/u/132162311?v=4" alt="Alexandre Ramos Profile Picture" class="mx-auto mb-4 rounded-full w-60 h-60">
                </a>
                    <h3 class="text-xl font-bold font-montserrat text-ultraViolet">Alexandre Ramos</h3>
                    
                </div>
                <div class="w-full text-center md:w-1/5">
                    <a href="https://github.com/g3raldes" aria-label="Filipa Geraldes GitHub Profile">
                        <img src="https://avatars.githubusercontent.com/u/116494634?v=4" alt="FIlipa Geraldes Profile Picture" class="mx-auto mb-4 rounded-full w-60 h-60">
                    </a>
                    <h3 class="text-xl font-bold font-montserrat text-ultraViolet">Filipa Geraldes</h3>
                    
                </div>
                <div class="w-full text-center md:w-1/5">
                    <a href="https://github.com/apilifogladif" aria-label="Filipa Fidalgo GitHub Profile">
                        <img src="https://avatars.githubusercontent.com/u/109923498?v=4" alt="Filipa Fidalgo Profile Picture" class="mx-auto mb-4 rounded-full w-60 h-60">
                    </a>
                    <h3 class="text-xl font-bold font-montserrat text-ultraViolet">Filipa Fidalgo</h3>
                    
                </div>
                <div class="w-full text-center md:w-1/5">
                    <a href="https://github.com/franciscoafonso04" aria-label="Francisco Afonso GitHub Profile">
                        <img src="https://avatars.githubusercontent.com/u/132215846?v=4" alt="Francisco Afonso Profile Picture" class="mx-auto mb-4 rounded-full w-60 h-60">
                    </a>
                    <h3 class="text-xl font-bold font-montserrat text-ultraViolet">Francisco Afonso</h3>
                    
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-bold font-montserrat text-ultraViolet">Get In Touch</h2>
            <p class="mt-4 text-xl font-montserrat text-eerieBlack">
                We would love to hear from you! If you have any questions, suggestions, or want to get involved, feel free to reach out.
            </p>
            <p class="mt-4 text-eerieBlack font-montserrat">
                Email us at <a href="mailto:info@lend-a-hand.com" class="text-blue-500">info@lend-a-hand.com</a>
            </p>
        </section>
    </div>
@endsection
