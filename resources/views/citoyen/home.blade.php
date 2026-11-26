
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123B4A">

    <title>PharmaFind | Trouvez votre pharmacie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #123B4A;
            --dark-navy: #0D303D;
            --teal: #16A6A0;
            --light-bg: #F5F8FA;
            --text: #243746;
            --muted: #82919B;
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--light-bg);
        }

        html {
            scroll-behavior: smooth;
        }

        .navbar {
            background: white;
            padding: 18px 0;
            box-shadow: 0 3px 20px rgba(18, 59, 74, .05);
        }

        .brand {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand span {
            color: var(--teal);
        }

        .navbar .nav-link {
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            margin-left: 22px;
        }

        .navbar .nav-link:hover {
            color: var(--teal);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 100px 0 90px;
            background: linear-gradient(135deg, #0D303D, #123B4A 65%, #17646A);
            color: white;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(22, 166, 160, .13);
            top: -180px;
            right: -100px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.12);
            bottom: -180px;
            left: 20%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 30px;
            color: #B9F0E8;
            background: rgba(255,255,255,.07);
            font-size: 12px;
            font-weight: 600;
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
            max-width: 540px;
            color: #D0E0E5;
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 32px;
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
            position: relative;
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
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,.15);
            font-size: 13px;
            font-weight: 700;
            animation: float 4s ease-in-out infinite;
        }

        .floating-card i {
            color: var(--teal);
            font-size: 20px;
            margin-right: 8px;
        }

        .card-one {
            top: 40px;
            right: 0;
        }

        .card-two {
            bottom: 35px;
            left: 0;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .search-section {
            position: relative;
            z-index: 5;
            margin-top: -45px;
        }

        .search-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 50px rgba(18,59,74,.10);
        }

        .section-title {
            color: var(--navy);
            font-weight: 800;
            font-size: 28px;
            letter-spacing: -.7px;
        }

        .section-description {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 9px;
        }

        .form-control,
        .form-select {
            min-height: 52px;
            border: 1px solid #E2E9ED;
            border-radius: 10px;
            font-size: 14px;
            padding: 12px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(22,166,160,.12);
        }

        .btn-primary-custom {
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 10px;
            min-height: 52px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            transition: .25s;
        }

        .btn-primary-custom:hover {
            background: #108D88;
            color: white;
            transform: translateY(-2px);
        }

        .btn-location {
            background: #E9F8F6;
            color: #108D88;
            border: 1px solid #C8EDE8;
            border-radius: 10px;
            min-height: 48px;
            padding: 10px 17px;
            font-weight: 700;
            font-size: 13px;
            transition: .2s;
        }

        .btn-location:hover {
            background: #D7F2EF;
        }

        .btn-location:disabled {
            opacity: .7;
            cursor: wait;
        }

        #locationStatus {
            font-size: 12px;
            line-height: 1.6;
            margin-top: 10px;
            min-height: 20px;
        }

        .features {
            padding: 100px 0;
        }

        .feature-card {
            height: 100%;
            padding: 30px;
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 17px;
            transition: .3s;
        }

        .feature-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(18,59,74,.08);
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E9F8F6;
            color: var(--teal);
            border-radius: 14px;
            font-size: 25px;
            margin-bottom: 22px;
        }

        .feature-card h5 {
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
            padding: 90px 0;
        }

        .step-number {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--navy);
            color: white;
            border-radius: 50%;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .step h5 {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
        }

        .step p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .cta {
            padding: 75px 0;
            background: linear-gradient(120deg, var(--navy), #17646A);
            color: white;
            text-align: center;
        }

        .cta h2 {
            font-weight: 800;
            font-size: 32px;
        }

        .cta p {
            color: #D0E0E5;
            font-size: 14px;
        }

        footer {
            background: var(--dark-navy);
            color: #B9C9CF;
            padding: 35px 0;
            font-size: 12px;
        }

        footer .brand {
            color: white;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media(max-width: 768px) {
            .hero {
                padding: 70px 0 85px;
            }

            .hero-visual {
                min-height: 290px;
                margin-top: 35px;
            }

            .visual-circle {
                width: 230px;
                height: 230px;
            }

            .visual-circle i {
                font-size: 85px;
            }

            .card-one {
                right: 0;
            }

            .card-two {
                left: 0;
            }

            .search-card {
                padding: 23px;
            }

            .features {
                padding: 70px 0;
            }
        }

        @media(prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="brand" href="{{ url('/') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Ouvrir le menu">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="#accueil">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#recherche">Rechercher</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#fonctionnement">Comment ça marche</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/login') }}" class="btn btn-primary-custom ms-lg-3 mt-3 mt-lg-0">
                        Espace professionnel
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="accueil">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-label">
                    <i class="bi bi-shield-check"></i>
                    Votre santé, notre priorité
                </div>

                <h1>
                    Trouvez une pharmacie
                    <span>près de vous.</span>
                </h1>

                <p class="hero-description">
                    PharmaFind vous accompagne pour localiser les pharmacies
                    en Tunisie et faciliter votre recherche grâce à une
                    expérience simple, rapide et intuitive.
                </p>

                <a href="#recherche" class="btn btn-primary-custom">
                    Trouver une pharmacie
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="col-lg-5">
                <div class="hero-visual">
                    <div class="visual-circle">
                        <i class="bi bi-hospital"></i>
                    </div>

                    <div class="floating-card card-one">
                        <i class="bi bi-geo-alt-fill"></i>
                        Localisation
                    </div>

                    <div class="floating-card card-two">
                        <i class="bi bi-heart-pulse-fill"></i>
                        PharmaFind
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH -->
<section class="search-section" id="recherche">
    <div class="container">
        <div class="search-card">
            <div class="row align-items-center mb-4">
                <div class="col-lg-8">
                    <h2 class="section-title">Rechercher une pharmacie</h2>
                    <p class="section-description mb-0">
                        Indiquez votre zone géographique ou utilisez votre
                        position pour préparer une recherche à proximité.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button type="button" class="btn-location" id="locationButton">
                        <i class="bi bi-crosshair me-2"></i>
                        Utiliser ma position
                    </button>
                    <div id="locationStatus" role="status" aria-live="polite"></div>
                </div>
            </div>

            <form action="{{ url('/recherche') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label" for="medicament">
                            Médicament ou pharmacie
                        </label>
                        <input type="text" class="form-control"
                               id="medicament" name="medicament"
                               placeholder="Nom du médicament ou de la pharmacie">
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="gouvernorat">
                            Gouvernorat
                        </label>
                        <select class="form-select" id="gouvernorat"
                                name="gouvernorat">
                            <option value="">Tous les gouvernorats</option>
                            <option value="Tunis">Tunis</option>
                            <option value="Ariana">Ariana</option>
                            <option value="Ben Arous">Ben Arous</option>
                            <option value="Manouba">Manouba</option>
                            <option value="Nabeul">Nabeul</option>
                            <option value="Zaghouan">Zaghouan</option>
                            <option value="Bizerte">Bizerte</option>
                            <option value="Béja">Béja</option>
                            <option value="Jendouba">Jendouba</option>
                            <option value="Kef">Le Kef</option>
                            <option value="Siliana">Siliana</option>
                            <option value="Sousse">Sousse</option>
                            <option value="Monastir">Monastir</option>
                            <option value="Mahdia">Mahdia</option>
                            <option value="Sfax">Sfax</option>
                            <option value="Kairouan">Kairouan</option>
                            <option value="Kasserine">Kasserine</option>
                            <option value="Sidi Bouzid">Sidi Bouzid</option>
                            <option value="Gabès">Gabès</option>
                            <option value="Médenine">Médenine</option>
                            <option value="Tataouine">Tataouine</option>
                            <option value="Gafsa">Gafsa</option>
                            <option value="Tozeur">Tozeur</option>
                            <option value="Kébili">Kébili</option>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="ville">
                            Ville / Localité
                        </label>
                        <input type="text" class="form-control"
                               id="ville" name="ville"
                               placeholder="Ex. : La Marsa">
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="col-12 mt-4">
                        <button type="submit"
                                class="btn btn-primary-custom w-100">
                            <i class="bi bi-search me-2"></i>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features" id="services">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="section-title">Une recherche plus simple</h2>
            <p class="section-description">
                Des outils pratiques pour faciliter votre recherche de pharmacie.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5>Localisation intelligente</h5>
                    <p>
                        Utilisez votre position géographique pour préparer
                        une recherche de pharmacies à proximité.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Recherche simplifiée</h5>
                    <p>
                        Recherchez les pharmacies par nom et par zone
                        géographique.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-map"></i>
                    </div>
                    <h5>Informations géographiques</h5>
                    <p>
                        Retrouvez les coordonnées et les informations
                        disponibles sur les pharmacies répertoriées.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="fonctionnement">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="section-title">Comment fonctionne PharmaFind ?</h2>
            <p class="section-description">
                Trois étapes pour faciliter votre recherche.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="step">
                    <div class="step-number">01</div>
                    <h5>Choisissez votre recherche</h5>
                    <p>
                        Saisissez le nom recherché et votre zone géographique.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="step">
                    <div class="step-number">02</div>
                    <h5>Utilisez votre localisation</h5>
                    <p>
                        Autorisez la géolocalisation si vous souhaitez
                        transmettre votre position.
                    </p>
                </div>
            </div>

            <div class="col-md-4 reveal">
                <div class="step">
                    <div class="step-number">03</div>
                    <h5>Consultez les résultats</h5>
                    <p>
                        Découvrez les pharmacies correspondantes et leurs
                        informations disponibles.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="container reveal">
        <h2>PharmaFind, votre recherche simplifiée.</h2>
        <p class="mt-3 mb-4">
            Commencez dès maintenant à rechercher une pharmacie.
        </p>
        <a href="#recherche" class="btn btn-primary-custom">
            Commencer la recherche
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <a class="brand" href="{{ url('/') }}">
                    <i class="bi bi-heart-pulse-fill"></i>
                    Pharma<span>Find</span>
                </a>
                <p class="mt-2 mb-0">
                    Faciliter l'accès à l'information sur les pharmacies.
                </p>
            </div>

            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    &copy; {{ date('Y') }} PharmaFind. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // GEOLOCALISATION DU CITOYEN
    const locationButton = document.getElementById('locationButton');
    const locationStatus = document.getElementById('locationStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    locationButton.addEventListener('click', function () {

        if (!navigator.geolocation) {
            locationStatus.textContent =
                "La géolocalisation n'est pas prise en charge par votre navigateur.";
            locationStatus.style.color = "#C0392B";
            return;
        }

        if (!window.isSecureContext) {
            locationStatus.textContent =
                "La géolocalisation nécessite HTTPS ou localhost.";
            locationStatus.style.color = "#C0392B";
            return;
        }

        locationButton.disabled = true;
        locationButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Localisation...';

        locationStatus.textContent =
            "Veuillez autoriser l'accès à votre position.";
        locationStatus.style.color = "#82919B";

        navigator.geolocation.getCurrentPosition(

            function (position) {

                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                latitudeInput.value = latitude;
                longitudeInput.value = longitude;

                locationStatus.innerHTML =
                    '<i class="bi bi-check-circle-fill me-1"></i>' +
                    'Position récupérée avec succès.';

                locationStatus.style.color = "#108D68";

                locationButton.disabled = false;
                locationButton.innerHTML =
                    '<i class="bi bi-geo-alt-fill me-2"></i>Position activée';

            },

            function (error) {

                latitudeInput.value = '';
                longitudeInput.value = '';

                let message = "Impossible de récupérer votre position.";

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message = "Vous avez refusé l'autorisation de localisation.";
                        break;

                    case error.POSITION_UNAVAILABLE:
                        message = "Votre position est actuellement indisponible.";
                        break;

                    case error.TIMEOUT:
                        message = "La demande de localisation a expiré. Réessayez.";
                        break;
                }

                locationStatus.textContent = message;
                locationStatus.style.color = "#C0392B";

                locationButton.disabled = false;
                locationButton.innerHTML =
                    '<i class="bi bi-crosshair me-2"></i>Réessayer';
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

    // ANIMATIONS AU DÉFILEMENT
    const revealElements = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12
        });

        revealElements.forEach(function (element) {
            observer.observe(element);
        });
    } else {
        revealElements.forEach(function (element) {
            element.classList.add('visible');
        });
    }
</script>

</body>
</html>
