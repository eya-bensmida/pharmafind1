@extends('citoyen.layout')

@section('title', 'Accueil')
@section('meta_description', 'PharmaFind facilite la recherche de médicaments dans les pharmacies agréées en Tunisie.')

@push('styles')
<style>
    /* Supprime l'espace vide laissé par le layout autour du contenu */
    main:has(.pf-home) {
        padding: 0 !important;
        margin: 0 !important;
    }

    main:has(.pf-home) + footer {
        margin-top: 0 !important;
    }

    .pf-home {
        --pf-navy: #123B4A;
        --pf-dark: #0D303D;
        --pf-teal: #16A6A0;
        --pf-light: #F5F8FA;
        --pf-muted: #687C85;
        color: var(--pf-navy);
        overflow: hidden;
    }

    .pf-home a { text-decoration: none; }

    /* Défilement fluide vers #services, #faq, etc. */
    html { scroll-behavior: smooth; }

    .pf-home [id] { scroll-margin-top: 90px; }

    /* HERO */
    .pf-hero {
        position: relative;
        min-height: 570px;
        display: flex;
        align-items: center;
        background:
            linear-gradient(90deg, rgba(13,48,61,.96) 0%,
            rgba(13,48,61,.83) 48%, rgba(13,48,61,.25) 100%),
            url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=1900&q=85')
            center/cover no-repeat;
        color: white;
    }

    .pf-hero-content { max-width: 650px; padding: 95px 0; }

    .pf-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 9px 16px;
        margin-bottom: 25px;
        border-radius: 30px;
        background: rgba(22,166,160,.18);
        border: 1px solid rgba(255,255,255,.2);
        color: #B8F2EC;
        font-size: 13px;
        font-weight: 700;
    }

    .pf-hero h1 {
        font-size: clamp(37px, 5vw, 60px);
        line-height: 1.13;
        letter-spacing: -1.8px;
        font-weight: 800;
        margin-bottom: 24px;
    }

    .pf-hero h1 span { color: #52D3C5; }

    .pf-hero p {
        max-width: 570px;
        color: #E0EAED;
        font-size: 16px;
        line-height: 1.9;
        margin-bottom: 30px;
    }

    .pf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 25px;
        border-radius: 9px;
        background: var(--pf-teal);
        color: white;
        font-weight: 700;
        transition: .25s;
    }

    .pf-btn:hover {
        background: #108D88;
        color: white;
        transform: translateY(-2px);
    }

    .pf-hero-note { margin-top: 24px; color: #D1E1E5; font-size: 13px; }

    /* TRUST */
    .pf-trust {
        padding: 25px 0;
        background: white;
        border-bottom: 1px solid #E8EFF1;
    }

    .pf-trust-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--pf-navy);
        font-size: 13px;
        font-weight: 700;
    }

    .pf-trust-item i { color: var(--pf-teal); font-size: 23px; }

    /* SECTIONS */
    .pf-section { padding: 85px 0; }
    .pf-section-light { background: var(--pf-light); }

    .pf-section-title {
        color: var(--pf-navy);
        font-size: clamp(27px, 3vw, 36px);
        font-weight: 800;
        letter-spacing: -.8px;
        margin-bottom: 15px;
    }

    .pf-section-subtitle {
        max-width: 650px;
        margin: 0 auto;
        color: var(--pf-muted);
        font-size: 15px;
        line-height: 1.8;
    }

    .pf-tag {
        display: inline-block;
        margin-bottom: 14px;
        color: var(--pf-teal);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    /* INTRO */
    .pf-intro-image {
        position: relative;
        padding: 20px;
        border-radius: 18px;
        background: linear-gradient(135deg, #E4F7F4, #F5F8FA);
    }

    .pf-intro-image svg {
        display: block;
        width: 100%;
        height: 410px;
    }

    .pf-image-label {
        position: absolute;
        bottom: 20px;
        left: 20px;
        padding: 14px 18px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 35px rgba(13,48,61,.12);
        color: var(--pf-navy);
        font-weight: 700;
        font-size: 13px;
    }

    .pf-intro-text { color: var(--pf-muted); font-size: 15px; line-height: 1.95; }

    .pf-check-list { list-style: none; padding: 0; margin: 25px 0; }

    .pf-check-list li {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 15px;
        color: var(--pf-navy);
        font-size: 14px;
    }

    .pf-check-list i { color: var(--pf-teal); font-size: 18px; }

    /* SEARCH BANNER */
    .pf-search-banner {
        padding: 48px;
        border-radius: 20px;
        background: linear-gradient(120deg, #0D303D, #17646A);
        color: white;
    }

    .pf-search-banner h2 { font-size: 29px; font-weight: 800; margin-bottom: 12px; }

    .pf-search-banner p {
        color: #D5E6E8;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .pf-btn-white {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 15px 23px;
        border-radius: 9px;
        background: white;
        color: var(--pf-navy);
        font-weight: 800;
        transition: .25s;
    }

    .pf-btn-white:hover {
        background: #E9F8F6;
        color: var(--pf-navy);
        transform: translateY(-2px);
    }

    /* SERVICES */
    .pf-service-card {
        height: 100%;
        overflow: hidden;
        border-radius: 16px;
        background: white;
        border: 1px solid #E8EFF1;
        transition: .3s;
    }

    .pf-service-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 16px 38px rgba(13,48,61,.11);
    }

    .pf-service-card > img,
    .pf-service-card > svg {
        display: block;
        width: 100%;
        height: 215px;
        object-fit: cover;
    }

    .pf-service-body { padding: 25px; }

    .pf-service-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: -49px;
        margin-bottom: 19px;
        position: relative;
        background: var(--pf-teal);
        color: white;
        border: 4px solid white;
        border-radius: 13px;
        font-size: 21px;
    }

    .pf-service-card h3 { font-size: 17px; font-weight: 800; margin-bottom: 12px; }

    .pf-service-card p {
        color: var(--pf-muted);
        font-size: 13px;
        line-height: 1.8;
        margin: 0;
    }

    /* STEPS */
    .pf-step {
        height: 100%;
        padding: 30px 25px;
        background: white;
        border: 1px solid #E7EEF0;
        border-radius: 15px;
    }

    .pf-step-number {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
        border-radius: 13px;
        background: #E7F7F5;
        color: var(--pf-teal);
        font-weight: 800;
        font-size: 17px;
    }

    .pf-step h3 { font-size: 16px; font-weight: 800; margin-bottom: 12px; }

    .pf-step p {
        color: var(--pf-muted);
        font-size: 13px;
        line-height: 1.8;
        margin: 0;
    }

    /* WHY */
    .pf-why-card {
        padding: 25px;
        height: 100%;
        background: white;
        border-radius: 14px;
        border: 1px solid #E8EFF1;
    }

    .pf-why-card i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 49px;
        height: 49px;
        margin-bottom: 18px;
        border-radius: 13px;
        background: #E8F8F6;
        color: var(--pf-teal);
        font-size: 22px;
    }

    .pf-why-card h3 { font-size: 16px; font-weight: 800; margin-bottom: 10px; }

    .pf-why-card p {
        color: var(--pf-muted);
        font-size: 13px;
        line-height: 1.8;
        margin: 0;
    }

    /* FAQ */
    .pf-faq .accordion-item {
        margin-bottom: 12px;
        overflow: hidden;
        border: 1px solid #E6EEF0;
        border-radius: 12px !important;
    }

    .pf-faq .accordion-button {
        padding: 20px;
        color: var(--pf-navy);
        font-size: 14px;
        font-weight: 700;
        box-shadow: none;
    }

    .pf-faq .accordion-button:not(.collapsed) {
        background: #EAF8F6;
        color: var(--pf-navy);
    }

    .pf-faq .accordion-body { color: var(--pf-muted); font-size: 13px; line-height: 1.9; }

    /* FINAL CTA */
    .pf-final-cta {
        padding: 75px 0;
        background: linear-gradient(120deg, #0D303D, #17646A);
        text-align: center;
        color: white;
    }

    .pf-final-cta h2 { font-size: clamp(27px, 3vw, 36px); font-weight: 800; margin-bottom: 15px; }
    .pf-final-cta p { color: #D7E7E9; margin-bottom: 27px; font-size: 14px; }

    /* CHATBOT FLOTTANT */
    .pharma-ai-launcher {
        position: fixed;
        right: 25px;
        bottom: 25px;
        z-index: 9998;
        width: 62px;
        height: 62px;
        border: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, #087f8c, #12a6a0);
        color: white;
        font-size: 25px;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(0,70,80,.25);
    }

    .pharma-ai-window {
        position: fixed;
        right: 25px;
        bottom: 100px;
        z-index: 9999;
        width: 380px;
        max-width: calc(100vw - 30px);
        max-height: calc(100dvh - 125px);
        display: none;
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0,40,50,.2);
        transition: width .25s ease;
    }

    .pharma-ai-window.open { display: block; }

    /* Fenêtre agrandie */
    .pharma-ai-window.expanded { width: 720px; }

    .pharma-ai-window.expanded .pharma-ai-messages {
        height: calc(100dvh - 330px);
        min-height: 300px;
    }

    .pharma-ai {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-height: calc(100dvh - 125px);
        margin: 0;
        background: #fff;
        border-radius: 18px;
        font-family: inherit;
    }

    .pharma-ai-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 20px;
        background: linear-gradient(120deg, #087f8c, #12a6a0);
        color: white;
    }

    .pharma-ai-header h3 { margin: 0 0 4px; font-size: 18px; }
    .pharma-ai-header p { margin: 0; font-size: 12px; opacity: .9; }

    .pharma-ai-icon {
        width: 43px;
        height: 43px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 13px;
        background: rgba(255,255,255,.2);
        font-size: 25px;
    }

    .pharma-ai-status {
        margin-left: auto;
        padding: 6px 10px;
        border-radius: 20px;
        background: rgba(255,255,255,.18);
        font-size: 11px;
    }

    .pharma-ai-expand,
    .pharma-ai-close {
        border: 0;
        background: transparent;
        color: white;
        cursor: pointer;
    }

    .pharma-ai-expand { font-size: 17px; }
    .pharma-ai-close { font-size: 19px; }

    .pharma-ai-messages {
        flex: 1;
        min-height: 0;
        height: 300px;
        padding: 18px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 13px;
        background: #fbfefe;
    }

    .pharma-ai-message {
        max-width: 85%;
        padding: 12px 15px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .pharma-ai-message.bot {
        align-self: flex-start;
        background: #eaf4f4;
        color: #24454a;
        border-bottom-left-radius: 4px;
    }

    .pharma-ai-message.user {
        align-self: flex-end;
        background: #087f8c;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .pharma-ai-form {
        display: flex;
        gap: 10px;
        padding: 15px;
        border-top: 1px solid #e9eeee;
    }

    .pharma-ai-form input {
        flex: 1;
        min-width: 0;
        border: 1px solid #d5e3e4;
        border-radius: 11px;
        padding: 13px 15px;
        outline: none;
        font-size: 14px;
    }

    .pharma-ai-form input:focus { border-color: #087f8c; }

    .pharma-ai-form button {
        border: 0;
        border-radius: 11px;
        padding: 0 16px;
        color: white;
        background: #087f8c;
        font-weight: 600;
        cursor: pointer;
    }

    .pharma-ai-form button:disabled { opacity: .6; cursor: wait; }

    .pharma-ai-note {
        margin: 0;
        padding: 0 15px 13px;
        color: #829397;
        font-size: 11px;
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .pf-hero { min-height: auto; background-position: 60% center; }
        .pf-hero-content { padding: 75px 12px; }
        .pf-section { padding: 60px 0; }
        .pf-intro-image svg { height: 290px; }
        .pf-search-banner { padding: 30px 22px; text-align: center; }
        .pf-search-banner h2 { font-size: 24px; }
        .pf-trust-item { justify-content: flex-start; padding: 8px 15px; }

        .pharma-ai-launcher { right: 16px; bottom: 16px; }

        .pharma-ai-window,
        .pharma-ai-window.expanded {
            right: 10px;
            bottom: 88px;
            width: calc(100vw - 20px);
            max-height: calc(100dvh - 105px);
        }

        .pharma-ai { max-height: calc(100dvh - 105px); }
        .pharma-ai-form { padding: 10px; }
        .pharma-ai-form button { padding: 0 12px; }
        .pharma-ai-header p { max-width: 150px; }

        /* Sur mobile la fenêtre est déjà quasi plein écran */
        .pharma-ai-expand { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }
        .pf-home *,
        .pf-home *::before,
        .pf-home *::after,
        .pharma-ai-window {
            transition-duration: .01ms !important;
        }
    }
</style>
@endpush

@section('content')

<div class="pf-home">

    {{-- HERO --}}
    <section class="pf-hero" id="accueil">
        <div class="container">
            <div class="pf-hero-content">
                <div class="pf-eyebrow">
                    <i class="bi bi-shield-check"></i>
                    Votre santé, notre priorité
                </div>

                <h1>
                    Trouvez vos médicaments
                    <span>plus facilement.</span>
                </h1>

                <p>
                    PharmaFind vous accompagne dans la recherche de
                    médicaments déclarés disponibles dans les pharmacies
                    agréées en Tunisie. Trouvez les pharmacies selon
                    votre localisation.
                </p>

                <a href="{{ route('citoyen.search.form') }}" class="pf-btn">
                    Rechercher un médicament
                    <i class="bi bi-arrow-right"></i>
                </a>

                <div class="pf-hero-note">
                    <i class="bi bi-geo-alt me-2"></i>
                    Recherche par GPS ou par localité
                </div>
            </div>
        </div>
    </section>

    {{-- TRUST STRIP --}}
    <section class="pf-trust">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="pf-trust-item">
                        <i class="bi bi-geo-alt"></i>
                        Recherche par localisation
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-trust-item">
                        <i class="bi bi-capsule"></i>
                        Recherche de médicaments
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-trust-item">
                        <i class="bi bi-map"></i>
                        Localisation des pharmacies
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- INTRODUCTION --}}
    <section class="pf-section">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <div class="pf-intro-image">
                        <svg viewBox="0 0 500 380" role="img"
                             aria-label="Illustration de pharmacie">
                            <rect width="500" height="380" rx="22" fill="#E4F7F4"/>
                            <circle cx="250" cy="175" r="125" fill="#C5EDE7"/>
                            <rect x="145" y="105" width="210" height="205" rx="18" fill="#FFFFFF"/>
                            <rect x="205" y="75" width="90" height="55" rx="12" fill="#16A6A0"/>
                            <path d="M250 85 V120 M232 102 H268" stroke="white"
                                  stroke-width="9" stroke-linecap="round"/>
                            <rect x="175" y="155" width="150" height="12" rx="6" fill="#DCEFEB"/>
                            <rect x="175" y="185" width="65" height="75" rx="9" fill="#EAF8F6"/>
                            <rect x="260" y="185" width="65" height="75" rx="9" fill="#EAF8F6"/>
                            <rect x="190" y="205" width="35" height="12" rx="6" fill="#16A6A0"/>
                            <rect x="201" y="194" width="13" height="34" rx="6" fill="#16A6A0"/>
                            <rect x="275" y="205" width="35" height="12" rx="6" fill="#16A6A0"/>
                            <rect x="286" y="194" width="13" height="34" rx="6" fill="#16A6A0"/>
                            <path d="M105 300 H395" stroke="#A4D9D1" stroke-width="8" stroke-linecap="round"/>
                            <circle cx="115" cy="105" r="18" fill="#16A6A0" opacity=".5"/>
                            <circle cx="385" cy="260" r="13" fill="#16A6A0" opacity=".5"/>
                        </svg>

                        <div class="pf-image-label">
                            <i class="bi bi-heart-pulse me-2" style="color:#16A6A0"></i>
                            PharmaFind en Tunisie
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="pf-tag">Bienvenue sur PharmaFind</span>

                    <h2 class="pf-section-title">
                        La recherche de médicaments devient plus simple
                    </h2>

                    <p class="pf-intro-text">
                        Trouver un médicament disponible peut parfois
                        demander du temps. PharmaFind facilite cette
                        recherche en mettant en relation les citoyens
                        avec les pharmacies agréées qui déclarent leurs
                        disponibilités.
                    </p>

                    <ul class="pf-check-list">
                        <li><i class="bi bi-check-circle-fill"></i><span>Recherchez un médicament facilement.</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Repérez les pharmacies selon votre localisation.</span></li>
                        <li><i class="bi bi-check-circle-fill"></i><span>Consultez les informations des pharmacies et leurs itinéraires.</span></li>
                    </ul>

                    <a href="{{ route('citoyen.search.form') }}" class="pf-btn">
                        Découvrir la recherche
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- SEARCH BANNER --}}
    <section class="pb-5">
        <div class="container">
            <div class="pf-search-banner">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h2>Vous recherchez un médicament ?</h2>
                        <p>
                            Lancez votre recherche et découvrez les
                            pharmacies qui déclarent disposer du
                            médicament souhaité.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('citoyen.search.form') }}" class="pf-btn-white">
                            Faire une recherche
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES --}}
    <section class="pf-section pf-section-light" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <span class="pf-tag">Nos services</span>
                <h2 class="pf-section-title">Découvrez les services PharmaFind</h2>
                <p class="pf-section-subtitle">
                    Des fonctionnalités pensées pour faciliter
                    la recherche de médicaments et la localisation
                    des pharmacies.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <article class="pf-service-card">
                        <img
                            src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=800&q=85"
                            alt="Médicaments et produits pharmaceutiques"
                            loading="lazy">
                        <div class="pf-service-body">
                            <div class="pf-service-icon"><i class="bi bi-capsule"></i></div>
                            <h3>Recherche de médicaments</h3>
                            <p>Recherchez le médicament souhaité et consultez les pharmacies qui déclarent sa disponibilité.</p>
                        </div>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="pf-service-card">
                        <img
                            src="https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=800&q=85"
                            alt="Intérieur d'une pharmacie"
                            loading="lazy">
                        <div class="pf-service-body">
                            <div class="pf-service-icon"><i class="bi bi-geo-alt"></i></div>
                            <h3>Pharmacies à proximité</h3>
                            <p>Identifiez les pharmacies selon votre position GPS, votre gouvernorat ou votre localité.</p>
                        </div>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="pf-service-card">
                        <img
                            src="https://images.pexels.com/photos/35969/pexels-photo.jpg?auto=compress&dpr=1&h=750&w=1260"
                            alt="Personne utilisant une carte GPS sur smartphone"
                            loading="lazy">
                        <div class="pf-service-body">
                            <div class="pf-service-icon"><i class="bi bi-signpost-2"></i></div>
                            <h3>Itinéraire vers la pharmacie</h3>
                            <p>Consultez les informations disponibles sur les pharmacies et accédez à leur itinéraire sur Google Maps.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="pf-section" id="fonctionnement">
        <div class="container">
            <div class="text-center mb-5">
                <span class="pf-tag">Comment ça marche ?</span>
                <h2 class="pf-section-title">Votre recherche en trois étapes</h2>
                <p class="pf-section-subtitle">PharmaFind vous accompagne tout au long de votre recherche.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="pf-step">
                        <div class="pf-step-number">01</div>
                        <h3>Recherchez votre médicament</h3>
                        <p>Saisissez le nom du médicament que vous souhaitez trouver.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-step">
                        <div class="pf-step-number">02</div>
                        <h3>Choisissez votre localisation</h3>
                        <p>Utilisez votre position GPS ou sélectionnez votre gouvernorat et votre localité.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-step">
                        <div class="pf-step-number">03</div>
                        <h3>Consultez les résultats</h3>
                        <p>Découvrez les pharmacies correspondantes, leurs informations et leurs itinéraires.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- WHY PHARMAFIND --}}
    <section class="pf-section pf-section-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="pf-tag">Pourquoi PharmaFind ?</span>
                <h2 class="pf-section-title">Une plateforme pensée pour vous</h2>
                <p class="pf-section-subtitle">
                    Une expérience simple pour rapprocher les citoyens
                    des informations disponibles auprès des pharmacies.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="pf-why-card">
                        <i class="bi bi-search"></i>
                        <h3>Recherche simplifiée</h3>
                        <p>Retrouvez plus facilement les pharmacies correspondant à votre recherche.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-why-card">
                        <i class="bi bi-geo-alt"></i>
                        <h3>Localisation pratique</h3>
                        <p>Utilisez la localisation pour rechercher les pharmacies dans votre secteur.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pf-why-card">
                        <i class="bi bi-info-circle"></i>
                        <h3>Informations accessibles</h3>
                        <p>Consultez les informations déclarées par les pharmacies sur la plateforme.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="pf-section" id="faq">
        <div class="container">
            <div class="text-center mb-5">
                <span class="pf-tag">FAQ</span>
                <h2 class="pf-section-title">Questions fréquentes</h2>
                <p class="pf-section-subtitle">
                    Quelques réponses pour mieux comprendre le fonctionnement de PharmaFind.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion pf-faq" id="pharmaFaq">

                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqOne"
                                        aria-expanded="true">
                                    Qu'est-ce que PharmaFind ?
                                </button>
                            </h3>
                            <div id="faqOne" class="accordion-collapse collapse show"
                                 data-bs-parent="#pharmaFaq">
                                <div class="accordion-body">
                                    PharmaFind est une plateforme qui facilite la recherche
                                    de médicaments et la localisation des pharmacies agréées en Tunisie.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqTwo"
                                        aria-expanded="false">
                                    Comment rechercher un médicament ?
                                </button>
                            </h3>
                            <div id="faqTwo" class="accordion-collapse collapse"
                                 data-bs-parent="#pharmaFaq">
                                <div class="accordion-body">
                                    Accédez au formulaire de recherche, saisissez le nom du médicament
                                    et indiquez votre localisation pour consulter les résultats disponibles.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqThree"
                                        aria-expanded="false">
                                    Comment trouver une pharmacie proche ?
                                </button>
                            </h3>
                            <div id="faqThree" class="accordion-collapse collapse"
                                 data-bs-parent="#pharmaFaq">
                                <div class="accordion-body">
                                    Vous pouvez utiliser la localisation GPS ou choisir une localité
                                    pour rechercher les pharmacies dans votre secteur.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqFour"
                                        aria-expanded="false">
                                    Les disponibilités sont-elles garanties ?
                                </button>
                            </h3>
                            <div id="faqFour" class="accordion-collapse collapse"
                                 data-bs-parent="#pharmaFaq">
                                <div class="accordion-body">
                                    Non. Les disponibilités affichées correspondent aux informations
                                    déclarées par les pharmacies et peuvent évoluer. Il est conseillé
                                    de contacter la pharmacie avant de vous déplacer.
                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqFive"
                                        aria-expanded="false">
                                    À quoi sert l'assistant PharmaFind AI ?
                                </button>
                            </h3>
                            <div id="faqFive" class="accordion-collapse collapse"
                                 data-bs-parent="#pharmaFaq">
                                <div class="accordion-body">
                                    L'assistant PharmaFind AI, accessible via la bulle de discussion
                                    en bas à droite de l'écran, vous aide à comprendre le
                                    fonctionnement de la plateforme et à vous orienter dans votre
                                    recherche. Il ne remplace ni un médecin ni un pharmacien, ne
                                    donne pas de conseil médical et ne garantit pas la disponibilité
                                    d'un médicament.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="pf-final-cta">
        <div class="container">
            <h2>Commencez votre recherche avec PharmaFind</h2>
            <p>
                Retrouvez les pharmacies correspondant à votre
                recherche partout en Tunisie.
            </p>
            <a href="{{ route('citoyen.search.form') }}" class="pf-btn-white">
                Faire une recherche
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </section>

</div>

{{-- CHATBOT FLOTTANT --}}
<button
    type="button"
    class="pharma-ai-launcher"
    id="pharmaAiLauncher"
    aria-label="Ouvrir l’assistant"
    aria-expanded="false"
    aria-controls="pharmaAiWindow">
    <i class="bi bi-chat-dots-fill"></i>
</button>

<div class="pharma-ai-window" id="pharmaAiWindow" aria-hidden="true">
    <section class="pharma-ai">

        <div class="pharma-ai-header">
            <div class="pharma-ai-icon">✦</div>

            <div>
                <h3>PharmaFind AI</h3>
                <p>Votre assistant PharmaFind</p>
            </div>

            <span class="pharma-ai-status">En ligne</span>

            <button
                type="button"
                class="pharma-ai-expand"
                id="pharmaAiExpand"
                aria-label="Agrandir l’assistant">
                <i class="bi bi-arrows-angle-expand"></i>
            </button>

            <button
                type="button"
                class="pharma-ai-close"
                id="pharmaAiClose"
                aria-label="Fermer l’assistant">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="pharma-ai-messages" id="pharmaAiMessages">
            <div class="pharma-ai-message bot">
                Bonjour ! Bienvenue sur PharmaFind. Comment puis-je vous aider ?
            </div>
        </div>

        <form class="pharma-ai-form" id="pharmaAiForm">
            <input
                type="text"
                id="pharmaAiInput"
                placeholder="Écrivez votre message..."
                maxlength="1000"
                autocomplete="off"
                required>

            <button type="submit" aria-label="Envoyer">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>

        <p class="pharma-ai-note">
            Assistant d'information PharmaFind.
        </p>

    </section>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const launcher = document.getElementById('pharmaAiLauncher');
    const chatWindow = document.getElementById('pharmaAiWindow');
    const closeButton = document.getElementById('pharmaAiClose');
    const expandButton = document.getElementById('pharmaAiExpand');

    const form = document.getElementById('pharmaAiForm');
    const input = document.getElementById('pharmaAiInput');
    const messages = document.getElementById('pharmaAiMessages');

    if (!launcher || !chatWindow || !closeButton) {
        return;
    }

    function openChat() {
        chatWindow.classList.add('open');
        chatWindow.setAttribute('aria-hidden', 'false');
        launcher.setAttribute('aria-expanded', 'true');

        if (input) input.focus();
    }

    function closeChat() {
        chatWindow.classList.remove('open');
        chatWindow.setAttribute('aria-hidden', 'true');
        launcher.setAttribute('aria-expanded', 'false');

        launcher.focus();
    }

    launcher.addEventListener('click', function () {
        if (chatWindow.classList.contains('open')) {
            closeChat();
        } else {
            openChat();
        }
    });

    closeButton.addEventListener('click', closeChat);

    // Agrandir / réduire la fenêtre
    if (expandButton) {
        expandButton.addEventListener('click', function () {
            const expanded = chatWindow.classList.toggle('expanded');
            const icon = expandButton.querySelector('i');

            icon.className = expanded
                ? 'bi bi-arrows-angle-contract'
                : 'bi bi-arrows-angle-expand';

            expandButton.setAttribute(
                'aria-label',
                expanded ? 'Réduire l’assistant' : 'Agrandir l’assistant'
            );

            if (messages) messages.scrollTop = messages.scrollHeight;
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && chatWindow.classList.contains('open')) {
            closeChat();
        }
    });

    function addMessage(text, type) {
        const message = document.createElement('div');
        message.className = 'pharma-ai-message ' + type;
        message.textContent = text;

        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;
    }

    if (form && input && messages) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const text = input.value.trim();
            if (!text) return;

            addMessage(text, 'user');
            input.value = '';

            addMessage(
                'Merci pour votre message. L’assistant est actuellement en mode démonstration.',
                'bot'
            );

            input.focus();
        });
    }
});
</script>
@endpush