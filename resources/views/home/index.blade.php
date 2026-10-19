@extends('layouts.app')

@section('title', 'Nucomu Cafe — Tempat Kembali, Rasa yang Dirindukan | Tulungagung')
@section('meta_description', 'Nucomu Cafe Tulungagung — Ruang santai monokrom hangat untuk menikmati seduhan kopi single origin, dessert artisanal, dan obrolan bermakna.')

@section('content')
    <!-- 1. Section Home / Hero (Flat-lay Overhead Composition) -->
    @include('home.sections.hero')

    <!-- 2. Section About (Cerita, Konsep & Fasilitas: Indoor, Outdoor, Musala, Toilet) -->
    @include('home.sections.about')

    <!-- 3. Section Menu (Signature Best Menu Switcher & Categories) -->
    @include('home.sections.best-menu')

    <!-- 4. Section Gallery (Asymmetric Editorial Collage & Lightbox) -->
    @include('home.sections.gallery')

    <!-- 6. Section Reviews (Google Reviews Hub with Honest Fallback, After Gallery & Before Layanan) -->
    @include('home.sections.reviews')

    <!-- 7. Section Layanan (Detail Layanan & Modal Reservasi WhatsApp) -->
    @include('home.sections.layanan')

    <!-- 8. Section Contact (Lokasi, Jam Operasional & Petunjuk Arah) -->
    @include('home.sections.contact')
@endsection
