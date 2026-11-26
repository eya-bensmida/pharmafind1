
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PharmaFind vous aide à trouver les pharmacies et rechercher vos médicaments en Tunisie.">
    <title>PharmaFind — Votre santé, plus proche de vous</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --navy: #123B4A;
            --navy-dark: #0D303D;
            --teal: #16A6A0;
            --teal-light: #E8F8F6;
            --background: #F5F8FA;
            --white: #FFFFFF;
            --text: #243746;
            --muted: #82919B;
            --border: #E7EEF1;
            --shadow: 0 18px 50px rgba(18, 59, 74, .09);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            padding: 18px 0;
            background: rgba(255,255,255,.94);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(231,238,241,.8);
        }

        .navbar-brand {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            margin-right: 8px;
            color: white;
            background: var(--teal);
            border-radius: 12px;
        }

        .navbar .nav-link {
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            margin: 0 10px;
            transition: .25s;
        }

        .navbar .nav-link:hover {
            color: var(--teal);
        }

        .btn-primary-custom {
            background: var(--teal);
            color: white;
            padding: 13px 23px;
            border-radius: 12px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            transition: .3s;
        }

        .btn-primary-custom:hover {
            background: var(--navy);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22,166,160,.2);
        }

        .btn-outline-custom {
            border: 1px solid #DCE7EA;
            color: var(--navy);
            background: white;
            padding: 13px 23px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: .3s;
        }

        .btn-outline-custom:hover {
            border-color: var(--teal);
            color: var(--teal);
            transform: translateY(-2px);
        }

        /* HERO */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 105px 0 95px;
            background:
                radial-gradient(circle at 85% 20%, rgba(22,166,160,.12), transparent 28%),
                linear-gradient(135deg, #F5F9FA 0%, #FFFFFF 65%);
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            right: -180px;
            bottom: -220px;
            border: 1px solid rgba(22,166,160,.15);
            border-radius: 50%;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--teal-light);
            color: #12857F;
            padding: 9px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .hero-badge span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--teal);
        }

        .hero h1 {
            color: var(--navy);
            font-size: clamp(38px, 5vw, 61px);
            font-weight: 800;
            line-height: 1.14;
            letter-spacing: -2.5px;
            margin-bottom: 23px;
        }

        .hero h1 span {
            color: var(--teal);
        }

        .hero-description {
            color: var(--muted);
            font-size: 16px;
            line-height: 1.9;
            max-width: 530px;
            margin-bottom: 30px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hero-visual {
            min-height: 420px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visual-circle {
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: linear-gradient(145deg, #E2F7F4, #F2FAFA);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .visual-cross {
            width: 180px;
            height: 180px;
            border-radius: 48px;
            background: linear-gradient(145deg, var(--teal), #0C8581);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 95px;
            box-shadow: 0 25px 60px rgba(22,166,160,.25);
            transform: rotate(-8deg);
        }

        .floating-card {
            position: absolute;
            background: white;
            padding: 15px 18px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: float 4s ease-in-out infinite;
        }

        .floating-card.one {
            top: 48px;
            right: 0;
        }

        .floating-card.two {
            bottom: 45px;
            left: 0;
            animation-delay: 1s;
        }

        .floating-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teal);
            background: var(--teal-light);
            font-size: 20px;
        }

        .floating-card strong {
            display: block;
            color: var(--navy);
            font-size: 12px;
        }

        .floating-card small {
            color: var(--muted);
            font-size: 10px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        /* SEARCH */

        .search-section {
            position: relative;
            z-index: 5;
            margin-top: -38px;
        }

        .search-box {
            background: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(18,59,74,.12);
            border: 1px solid var(--border);
        }

        .search-heading {
            font-size: 18px;
            color: var(--navy);
            font-weight: 800;
            margin-bottom: 20px;
        }

        .form-label {
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .search-input {
            height: 52px;
            border: 1px solid var(--border);
            border-radius: 11px;
            font-size: 13px;
            padding: 0 14px;
            box-shadow: none !important;
        }

        .search-input:focus {
            border-color: var(--teal);
        }

        .search-input::placeholder {
            color: #AAB6BC;
        }

        .search-submit {
            height: 52px;
            width: 100%;
            border-radius: 11px;
            border: none;
            background: var(--navy);
            color: white;
            font-weight: 700;
            font-size: 13px;
            transition: .3s;
        }

        .search-submit:hover {
            background: var(--teal);
        }

        .location-button {
            color: var(--teal);
            border: none;
            background: transparent;
            font-size: 12px;
            font-weight: 700;
            margin-top: 15px;
        }

        /* COMMON */

        .section {
            padding: 95px 0;
        }

        .section-soft {
            background: var(--background);
        }

        .section-label {
            color: var(--teal);
            text-transform: uppercase;
            letter-spacing: 1.7px;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .section-title {
            color: var(--navy);
            font-size: clamp(28px, 3vw, 39px);
            font-weight: 800;
            letter-spacing: -1.2px;
            line-height: 1.25;
            margin-bottom: 15px;
        }

        .section-description {
            color: var(--muted);
            line-height: 1.8;
            font-size: 14px;
            max-width: 580px;
        }

        /* FEATURES */

        .feature-card {
            height: 100%;
            padding: 30px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow);
            border-color: transparent;
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--teal-light);
            color: var(--teal);
            border-radius: 15px;
            font-size: 24px;
            margin-bottom: 22px;
        }

        .feature-card h3 {
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
            margin: 0;
        }

        /* MAP PREVIEW */

        .map-preview {
            min-height: 370px;
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            background-color: #E8F1EF;
            background-image:
                linear-gradient(30deg, transparent 48%, rgba(255,255,255,.85) 49%, rgba(255,255,255,.85) 52%, transparent 53%),
                linear-gradient(120deg, transparent 46%, rgba(255,255,255,.8) 47%, rgba(255,255,255,.8) 50%, transparent 51%);
            background-size: 90px 90px, 120px 120px;
        }

        .map-road {
            position: absolute;
            width: 130%;
            height: 24px;
            background: rgba(255,255,255,.85);
            transform: rotate(-25deg);
            top: 46%;
            left: -10%;
        }

        .map-road.second {
            transform: rotate(35deg);
            top: 60%;
        }

        .map-water {
            position: absolute;
            width: 170px;
            height: 450px;
            right: 5%;
            top: -40px;
            background: rgba(166,220,218,.45);
            transform: rotate(35deg);
            border-radius: 50%;
        }

        .map-pin {
            position: absolute;
            z-index: 2;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% 50% 50% 8px;
            background: var(--teal);
            color: white;
            font-size: 18px;
            transform: rotate(-45deg);
            box-shadow: 0 8px 20px rgba(18,59,74,.2);
        }

        .map-pin i {
            transform: rotate(45deg);
        }

        .pin-one { top: 27%; left: 25%; }
        .pin-two { top: 57%; left: 57%; }
        .pin-three { top: 23%; right: 18%; }

        .map-label {
            position: absolute;
            z-index: 3;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: white;
            border-radius: 13px;
            padding: 14px 17px;
            box-shadow: var(--shadow);
            font-size: 12px;
            color: var(--navy);
            font-weight: 700;
        }

        /* STEPS */

        .step-card {
            text-align: center;
            padding: 25px 18px;
        }

        .step-number {
            width: 62px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border-radius: 18px;
            background: var(--teal-light);
            color: var(--teal);
            font-size: 24px;
            font-weight: 800;
        }

        .step-card h3 {
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .step-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* CTA */

        .cta-box {
            position: relative;
            overflow: hidden;
            background: linear-gradient(120deg, var(--navy), #185568);
            border-radius: 24px;
            padding: 55px;
            color: white;
        }

        .cta-box h2 {
            font-size: clamp(25px, 3vw, 36px);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 15px;
        }

        .cta-box p {
            color: #C5D9DE;
            font-size: 14px;
            line-height: 1.8;
            max-width: 550px;
        }

        .cta-decoration {
            position: absolute;
            width: 280px;
            height: 280px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
            right: -50px;
            top: -120px;
        }

        /* FOOTER */

        footer {
            background: var(--navy-dark);
            color: white;
            padding: 65px 0 25px;
        }

        .footer-brand {
            color: white;
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 15px;
            display: inline-block;
        }

        .footer-description {
            color: #AFC4CA;
            font-size: 13px;
            line-height: 1.9;
            max-width: 330px;
        }

        footer h4 {
            color: white;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer li {
            margin-bottom: 12px;
        }

        footer li a {
            color: #AFC4CA;
            font-size: 12px;
            transition: .2s;
        }

        footer li a:hover {
            color: var(--teal);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 40px;
            padding-top: 22px;
            color: #AFC4CA;
            font-size: 11px;
        }

        /* ANIMATIONS */

        .reveal {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
            .reveal {
                opacity: 1;
                transform: none;
            }
        }

        /* RESPONSIVE */

        @media (max-width: 991px) {
            .hero {
                padding: 75px 0 80px;
            }

            .hero-visual {
                min-height: 350px;
                margin-top: 35px;
            }

            .visual-circle {
                width: 280px;
                height: 280px;
            }

            .visual-cross {
                width: 145px;
                height: 145px;
                font-size: 75px;
            }

            .section {
                padding: 70px 0;
            }
        }

        @media (max-width: 575px) {
            .hero {
                padding: 65px 0 70px;
            }

            .hero h1 {
                letter-spacing: -1.5px;
            }

            .hero-visual {
                min-height: 300px;
            }

            .visual-circle {
                width: 235px;
                height: 235px;
            }

            .visual-cross {
                width: 120px;
                height: 120px;
                border-radius: 35px;
                font-size: 65px;
            }

            .floating-card {
                padding: 10px;
            }

            .floating-card.one {
                right: -5px;
            }

            .floating-card.two {
                left: -5px;
            }

            .search-box {
                padding: 20px;
            }

            .cta-box {
                padding: 35px 25px;
            }

            .section {
                padding: 60px 0;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="brand-icon"><i class="bi bi-plus-lg"></i></span>
            PharmaFind
        </a>

        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Ouvrir le menu">
            <i class="bi bi-list fs-2" style="color:var(--navy)"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Nos services</a></li>
                <li class="nav-item"><a class="nav-link" href="#fonctionnement">Comment ça marche</a></li>
                <li class="nav-item"><a class="nav-link" href="#apropos">À propos</a></li>
            </ul>

            <a href="{{ url('/recherche') }}" class="btn btn-primary-custom">
                Commencer <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 reveal">
                <div class="hero-badge">
                    <span></span>
                    Votre plateforme de recherche pharmaceutique
                </div>

                <h1>
                    La bonne pharmacie,
                    <span>au bon endroit.</span>
                </h1>

                <p class="hero-description">
                    PharmaFind vous accompagne pour localiser les pharmacies
                    et rechercher les médicaments disponibles près de vous.
                    La santé devient plus accessible, simplement.
                </p>

                <div class="hero-actions">
                    <a href="#recherche" class="btn btn-primary-custom">
                        Trouver une pharmacie
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <a href="#fonctionnement" class="btn btn-outline-custom">
                        Découvrir PharmaFind
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual reveal">
                    <div class="visual-circle">
                        <div class="visual-cross">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                    </div>

                    <div class="floating-card one">
                        <div class="floating-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <strong>Localisation facile</strong>
                            <small>Autour de votre position</small>
                        </div>
                    </div>

                    <div class="floating-card two">
                        <div class="floating-icon">
                            <i class="bi bi-capsule-pill"></i>
                        </div>
                        <div>
                            <strong>Recherche simplifiée</strong>
                            <small>Pharmacies et médicaments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH -->
<section class="search-section" id="recherche">
    <div class="container">
        <div class="search-box reveal">
            <div class="search-heading">
                <i class="bi bi-search me-2" style="color:var(--teal)"></i>
                Trouvez ce dont vous avez besoin
            </div>

            <form action="{{ url('/recherche') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Médicament ou pharmacie</label>
                        <input type="text" name="medicament"
                               class="form-control search-input"
                               placeholder="Ex. : Paracétamol ou nom de pharmacie">
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Gouvernorat</label>
                        <select name="gouvernorat" class="form-select search-input">
                            <option value="">Tous les gouvernorats</option>
                            <option value="Tunis">Tunis</option>
                            <option value="Ariana">Ariana</option>
                            <option value="Ben Arous">Ben Arous</option>
                            <option value="Manouba">Manouba</option>
                            <option value="Nabeul">Nabeul</option>
                            <option value="Sousse">Sousse</option>
                            <option value="Monastir">Monastir</option>
                            <option value="Sfax">Sfax</option>
                            <option value="Bizerte">Bizerte</option>
                            <option value="Gabès">Gabès</option>
                            <option value="Kairouan">Kairouan</option>
                            <option value="Mahdia">Mahdia</option>
                            <option value="Gafsa">Gafsa</option>
                            <option value="Médenine">Médenine</option>
                            <option value="Jendouba">Jendouba</option>
                            <option value="Kasserine">Kasserine</option>
                            <option value="Kébili">Kébili</option>
                            <option value="Le Kef">Le Kef</option>
                            <option value="Siliana">Siliana</option>
                            <option value="Sidi Bouzid">Sidi Bouzid</option>
                            <option value="Tataouine">Tataouine</option>
                            <option value="Tozeur">Tozeur</option>
                            <option value="Zaghouan">Zaghouan</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Ville ou localité</label>
                        <input type="text" name="ville"
                               class="form-control search-input"
                               placeholder="Ex. : La Marsa">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="search-submit">
                            <i class="bi bi-search me-2"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>

            <button type="button" class="location-button" id="locationButton">
                <i class="bi bi-crosshair2 me-1"></i>
                <span id="locationText">Utiliser ma position actuelle</span>
            </button>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section" id="services">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="section-label">Nos services</div>
            <h2 class="section-title">Tout ce qu'il vous faut,<br>au même endroit.</h2>
            <p class="section-description mx-auto">
                Une expérience simple et pratique pour vous aider à trouver
                les informations pharmaceutiques dont vous avez besoin.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-capsule-pill"></i>
                    </div>
                    <h3>Recherche de médicaments</h3>
                    <p>
                        Recherchez un médicament et consultez les informations
                        de disponibilité lorsqu'elles ont été déclarées
                        par une pharmacie.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h3>Pharmacies à proximité</h3>
                    <p>
                        Découvrez les pharmacies répertoriées selon votre
                        gouvernorat, votre ville ou votre position.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-sign-turn-right"></i>
                    </div>
                    <h3>Itinéraire simplifié</h3>
                    <p>
                        Consultez l'emplacement d'une pharmacie et accédez
                        à son itinéraire grâce à Google Maps.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAP -->
<section class="section section-soft">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 reveal">
                <div class="section-label">La proximité avant tout</div>
                <h2 class="section-title">
                    Trouvez une pharmacie près de vous.
                </h2>
                <p class="section-description mb-4">
                    Explorez les pharmacies répertoriées et consultez
                    leurs informations géographiques. Choisissez celle
                    qui correspond à votre recherche.
                </p>

                <a href="{{ url('/recherche') }}" class="btn btn-primary-custom">
                    Explorer les pharmacies
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="col-lg-7 reveal">
                <div class="map-preview">
                    <div class="map-water"></div>
                    <div class="map-road"></div>
                    <div class="map-road second"></div>

                    <div class="map-pin pin-one">
                        <i class="bi bi-plus-lg"></i>
                    </div>

                    <div class="map-pin pin-two">
                        <i class="bi bi-plus-lg"></i>
                    </div>

                    <div class="map-pin pin-three">
                        <i class="bi bi-plus-lg"></i>
                    </div>

                    <div class="map-label">
                        <i class="bi bi-geo-alt-fill me-2" style="color:var(--teal)"></i>
                        Les pharmacies seront affichées sur la carte après votre recherche.
                    </div>
                </div>
                <p class="small mt-2 mb-0" style="color:var(--muted)">
                    Illustration de présentation — les résultats cartographiques
                    réels seront chargés depuis les données disponibles.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" id="fonctionnement">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <div class="section-label">Simple et pratique</div>
            <h2 class="section-title">Comment fonctionne PharmaFind ?</h2>
            <p class="section-description mx-auto">
                Trois étapes pour trouver les informations que vous recherchez.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="step-card">
                    <div class="step-number">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>01. Effectuez votre recherche</h3>
                    <p>
                        Saisissez le nom d'un médicament ou recherchez
                        une pharmacie par localisation.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="step-card">
                    <div class="step-number">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <h3>02. Consultez les résultats</h3>
                    <p>
                        Découvrez les pharmacies correspondantes et
                        consultez leurs informations disponibles.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="step-card">
                    <div class="step-number">
                        <i class="bi bi-map"></i>
                    </div>
                    <h3>03. Consultez l'itinéraire</h3>
                    <p>
                        Choisissez une pharmacie et ouvrez son itinéraire
                        sur Google Maps.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="pb-5">
    <div class="container">
        <div class="cta-box reveal">
            <div class="cta-decoration"></div>
            <div class="row align-items-center position-relative">
                <div class="col-lg-8">
                    <h2>La pharmacie qu'il vous faut, plus facilement.</h2>
                    <p>
                        Commencez votre recherche avec PharmaFind et
                        consultez les pharmacies répertoriées près de vous.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ url('/recherche') }}" class="btn btn-light rounded-3 px-4 py-3 fw-bold">
                        Commencer maintenant
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer id="apropos">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <a href="{{ url('/') }}" class="footer-brand">
                    <i class="bi bi-plus-circle-fill me-2" style="color:var(--teal)"></i>
                    PharmaFind
                </a>

                <p class="footer-description">
                    PharmaFind est une plateforme destinée à faciliter
                    la recherche de pharmacies et l'accès aux informations
                    pharmaceutiques en Tunisie.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Accueil</a></li>
                    <li><a href="#services">Nos services</a></li>
                    <li><a href="#fonctionnement">Fonctionnement</a></li>
                    <li><a href="#apropos">À propos</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h4>Recherche</h4>
                <ul>
                    <li><a href="{{ url('/recherche') }}">Pharmacies</a></li>
                    <li><a href="{{ url('/recherche') }}">Médicaments</a></li>
                    <li><a href="{{ url('/recherche') }}">Localisation</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h4>Notre engagement</h4>
                <p class="footer-description">
                    Faciliter l'accès à l'information et rapprocher
                    les citoyens des pharmacies.
                </p>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>© {{ date('Y') }} PharmaFind. Tous droits réservés.</span>
            <span>Une recherche plus simple, une santé plus proche.</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Apparition progressive des sections
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(element => {
        revealObserver.observe(element);
    });

    // Géolocalisation du citoyen
    const locationButton = document.getElementById('locationButton');
    const locationText = document.getElementById('locationText');

    locationButton.addEventListener('click', () => {
        if (!navigator.geolocation) {
            locationText.textContent = "La géolocalisation n'est pas disponible.";
            return;
        }

        locationText.textContent = "Localisation en cours...";

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                const form = document.querySelector('.search-box form');

                let latInput = form.querySelector('[name="latitude"]');
                let lonInput = form.querySelector('[name="longitude"]');

                if (!latInput) {
                    latInput = document.createElement('input');
                    latInput.type = 'hidden';
                    latInput.name = 'latitude';
                    form.appendChild(latInput);
                }

                if (!lonInput) {
                    lonInput = document.createElement('input');
                    lonInput.type = 'hidden';
                    lonInput.name = 'longitude';
                    form.appendChild(lonInput);
                }

                latInput.value = latitude;
                lonInput.value = longitude;

                locationText.textContent = "Position ajoutée à votre recherche.";
            },
            () => {
                locationText.textContent = "Impossible d'obtenir votre position. Vérifiez l'autorisation du navigateur.";
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });
</script>

</body>
</html>
