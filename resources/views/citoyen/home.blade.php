
@extends('citoyen.layout')

@section('title', 'Accueil')
@section('meta_description', 'PharmaFind vous aide à rechercher des médicaments dans les pharmacies agréées en Tunisie.')

@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

    .hero {
        position: relative;
        overflow: hidden;
        padding: 95px 0;
        background: linear-gradient(135deg, #0D303D, #123B4A 65%, #17646A);
        color: white;
    }

    .hero::before {
        content: "";
        position: absolute;
        width: 450px;
        height: 450px;
        border-radius: 50%;
        background: rgba(22,166,160,.13);
        top: -200px;
        right: -100px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-label {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 9px 15px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 30px;
        color: #B9F0E8;
        background: rgba(255,255,255,.07);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .hero h1 {
        font-size: clamp(36px, 5vw, 58px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -2px;
        margin-bottom: 22px;
    }

    .hero h1 span {
        color: #52D3C5;
    }

    .hero-description {
        max-width: 550px;
        color: #D0E0E5;
        line-height: 1.9;
        font-size: 15px;
        margin-bottom: 30px;
    }

    .hero-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 53px;
        padding: 13px 24px;
        border-radius: 10px;
        background: var(--teal);
        color: white;
        font-size: 14px;
        font-weight: 700;
        transition: .25s;
    }

    .hero-button:hover {
        background: #108D88;
        color: white;
        transform: translateY(-2px);
    }

    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 350px;
    }

    .visual-circle {
        width: 290px;
        height: 290px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .visual-circle i {
        font-size: 115px;
        color: #52D3C5;
    }

    .floating-card {
        position: absolute;
        background: white;
        color: var(--navy);
        padding: 15px 18px;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,.15);
        font-size: 13px;
        font-weight: 700;
    }

    .floating-card i {
        color: var(--teal);
        font-size: 19px;
        margin-right: 8px;
    }

    .card-one {
        top: 40px;
        right: 0;
    }

    .card-two {
        bottom: 35px;
        left: 0;
    }

    .features {
        padding: 85px 0;
    }

    .section-heading {
        color: var(--navy);
        font-size: 29px;
        font-weight: 800;
        letter-spacing: -.7px;
    }

    .section-description {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.8;
    }

    .feature-card {
        height: 100%;
        padding: 30px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 17px;
        transition: .3s;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow);
    }

    .feature-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E9F8F6;
        color: var(--teal);
        border-radius: 14px;
        font-size: 25px;
        margin-bottom: 22px;
    }

    .feature-card h3 {
        color: var(--navy);
        font-weight: 800;
        font-size: 17px;
        margin-bottom: 12px;
    }

    .feature-card p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
        margin: 0;
    }

    .how-section {
        background: white;
        padding: 85px 0;
    }

    .step-card {
        height: 100%;
        padding: 25px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: white;
    }

    .step-number {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--navy);
        color: white;
        border-radius: 14px;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .step-card h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--navy);
    }

    .step-card p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .cta-section {
        padding: 75px 0;
        background: linear-gradient(120deg, var(--navy), #17646A);
        color: white;
        text-align: center;
    }

    .cta-section h2 {
        font-size: 31px;
        font-weight: 800;
    }

    .cta-section p {
        color: #D0E0E5;
        font-size: 14px;
    }

    @media(max-width: 767px) {
        .hero {
            padding: 65px 0;
        }

        .hero-visual {
            min-height: 270px;
            margin-top: 25px;
        }

        .visual-circle {
            width: 220px;
            height: 220px;
        }

        .visual-circle i {
            font-size: 85px;
        }

        .features,
        .how-section {
            padding: 65px 0;
        }

        .section-heading {
            font-size: 25px;
        }

        .cta-section h2 {
            font-size: 25px;
        }
    }

    @media(prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
        }
    }
</style>
@endpush

@section('content')

<section class="hero" id="accueil">
    <div class="container hero-content">
        <div class="row align-items-center">

            <div class="col-lg-7">
                <div class="hero-label">
                    <i class="bi bi-shield-check"></i>
                    Votre santé, notre priorité
                </div>

                <h1>
                    Trouvez votre médicament
                    <span>près de vous.</span>
                </h1>

                <p class="hero-description">
                    PharmaFind vous aide à rechercher les médicaments
                    déclarés disponibles dans les pharmacies agréées
                    en Tunisie, selon votre localisation.
                </p>

                <a href="{{ route('citoyen.search.form') }}"
                   class="hero-button">
                    Faire une recherche
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>

                <div class="mt-4 small text-white-50">
                    <i class="bi bi-geo-alt me-1"></i>
                    Recherche par GPS ou par localité
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-visual">
                    <div class="visual-circle">
                        <i class="bi bi-hospital"></i>
                    </div>

                    <div class="floating-card card-one">
                        <i class="bi bi-geo-alt-fill"></i>
                        Proximité
                    </div>

                    <div class="floating-card card-two">
                        <i class="bi bi-capsule"></i>
                        PharmaFind
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="features" id="services">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-heading">
                Une recherche plus simple
            </h2>

            <p class="section-description mt-3">
                Des outils pratiques pour faciliter votre recherche
                de médicaments.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <h3>Recherche par localisation</h3>

                    <p>
                        Utilisez votre position géographique ou choisissez
                        un gouvernorat et une localité.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-capsule"></i>
                    </div>

                    <h3>Recherche de médicaments</h3>

                    <p>
                        Recherchez un médicament et consultez les pharmacies
                        qui déclarent en avoir en stock.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-map"></i>
                    </div>

                    <h3>Itinéraire vers la pharmacie</h3>

                    <p>
                        Consultez les informations des pharmacies et
                        ouvrez leur itinéraire sur Google Maps.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="how-section" id="fonctionnement">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="section-heading">
                Comment fonctionne PharmaFind ?
            </h2>

            <p class="section-description mt-3">
                Trois étapes pour faciliter votre recherche.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">01</div>

                    <h3>Choisissez votre médicament</h3>

                    <p>
                        Saisissez le nom du médicament et choisissez
                        le type de pharmacie souhaité.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">02</div>

                    <h3>Indiquez votre localisation</h3>

                    <p>
                        Choisissez votre gouvernorat et votre localité,
                        ou utilisez votre position GPS.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">03</div>

                    <h3>Consultez les résultats</h3>

                    <p>
                        Découvrez les pharmacies correspondantes,
                        leurs stocks déclarés et leurs itinéraires.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">

        <h2>PharmaFind, votre recherche simplifiée.</h2>

        <p class="mt-3 mb-4">
            Commencez dès maintenant à rechercher un médicament.
        </p>

        <a href="{{ route('citoyen.search.form') }}"
           class="hero-button">
            Faire une recherche
            <i class="bi bi-arrow-right ms-2"></i>
        </a>

    </div>
</section>

@endsection
