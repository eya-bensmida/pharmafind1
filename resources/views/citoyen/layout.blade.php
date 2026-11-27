<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PharmaFind') | PharmaFind</title>

    <meta name="description" content="@yield('meta_description', 'PharmaFind vous aide à trouver les pharmacies et les médicaments à proximité.')">

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
            --border: #E5ECEF;
            --shadow: 0 8px 28px rgba(18, 59, 74, 0.07);
        }

        * {
            box-sizing: border-box;
        }

        /* Défilement fluide vers les ancres (#services, #faq...) */
        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
        }

        /* NAVBAR */
        .main-navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(18, 59, 74, 0.04);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .brand-logo:hover {
            color: var(--teal);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background: var(--teal);
            font-size: 23px;
        }

        .navbar-nav .nav-link {
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            padding: 10px 14px !important;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--teal);
        }

        .navbar-nav .nav-link.active {
            font-weight: 800;
        }

        .navbar-toggler {
            border-color: var(--border);
            box-shadow: none !important;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* BUTTONS */
        .btn-primary-custom {
            background: var(--teal);
            color: white;
            border: 1px solid var(--teal);
            border-radius: 10px;
            padding: 11px 20px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: #128D88;
            border-color: #128D88;
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            background: white;
            color: var(--navy);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-outline-custom:hover {
            border-color: var(--teal);
            color: var(--teal);
        }

        /* Bouton Espace professionnel (dans la navbar) */
        .btn-pro {
            display: inline-flex;
            align-items: center;
            background: white;
            color: var(--navy);
            border: 1px solid var(--navy);
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-pro:hover {
            background: var(--navy);
            border-color: var(--navy);
            color: white;
        }

        /* PAGE */
        .page-content {
            flex: 1;
        }

        .section-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .section-title {
            color: var(--navy);
            font-weight: 800;
            letter-spacing: -0.6px;
        }

        .text-muted-custom {
            color: var(--muted);
        }

        /* FOOTER */
        .main-footer {
            background: var(--dark-navy);
            color: rgba(255, 255, 255, 0.75);
            margin-top: 70px;
            padding: 35px 0 22px;
        }

        .footer-brand {
            color: white;
            font-size: 20px;
            font-weight: 800;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
        }

        .footer-link:hover {
            color: white;
        }

        /* MOBILE */
        @media (max-width: 767px) {
            .brand-logo {
                font-size: 20px;
            }

            .main-navbar {
                padding: 10px 0;
            }

            .main-footer {
                margin-top: 45px;
            }
        }

        /* MENU REPLIÉ (en dessous de 1200px) */
        @media (max-width: 1199px) {
            .navbar-nav {
                padding-top: 12px;
            }

            .navbar-nav .nav-link {
                padding: 10px 4px !important;
            }

            .navbar-nav .nav-item {
                margin-left: 0 !important;
            }

            .navbar-nav .btn-pro,
            .navbar-nav .btn-primary-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- NAVBAR COMMUNE CITOYEN -->
    <nav class="navbar navbar-expand-xl main-navbar">
        <div class="container">

            <a class="brand-logo" href="{{ route('citoyen.home') }}">
                <span class="brand-icon">
                    <i class="bi bi-capsule"></i>
                </span>
                <span>PharmaFind</span>
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#citizenNavbar"
                    aria-controls="citizenNavbar"
                    aria-expanded="false"
                    aria-label="Ouvrir le menu">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="citizenNavbar">

                <ul class="navbar-nav ms-auto align-items-xl-center">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('citoyen.home') ? 'active' : '' }}"
                           href="{{ route('citoyen.home') }}"
                           @if(request()->routeIs('citoyen.home')) aria-current="page" @endif>
                            <i class="bi bi-house-door me-1"></i>
                            Accueil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           data-section="services"
                           href="{{ route('citoyen.home') }}#services">
                            <i class="bi bi-grid me-1"></i>
                            Fonctionnalités
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           data-section="faq"
                           href="{{ route('citoyen.home') }}#faq">
                            <i class="bi bi-question-circle me-1"></i>
                            FAQ
                        </a>
                    </li>

                    <li class="nav-item ms-xl-2 mt-2 mt-xl-0">
                        <a class="btn btn-pro"
                           href="{{ route('login') }}">
                            <i class="bi bi-person-badge me-1"></i>
                            Espace professionnel
                        </a>
                    </li>

                    <li class="nav-item ms-xl-2 mt-2 mt-xl-0">
                        <a class="btn btn-primary-custom"
                           href="{{ route('citoyen.search.form') }}">
                            <i class="bi bi-search me-1"></i>
                            Faire une recherche
                        </a>
                    </li>

                </ul>
            </div>

        </div>
    </nav>

    <!-- CONTENU DES PAGES -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- FOOTER COMMUN -->
    <footer class="main-footer">
        <div class="container">

            <div class="row gy-4 align-items-center">

                <div class="col-md-6">
                    <div class="footer-brand mb-2">
                        <i class="bi bi-capsule me-2"></i>
                        PharmaFind
                    </div>

                    <p class="small mb-0">
                        Faciliter la recherche de pharmacies et de médicaments
                        à proximité.
                    </p>
                </div>

                <div class="col-md-6 text-md-end">
                    <a href="{{ route('citoyen.home') }}" class="footer-link me-3">
                        Accueil
                    </a>

                    <a href="{{ route('citoyen.search.form') }}" class="footer-link me-3">
                        Recherche
                    </a>

                    <a href="{{ route('citoyen.home') }}#faq" class="footer-link me-3">
                        FAQ
                    </a>

                    <a href="{{ route('login') }}" class="footer-link">
                        Espace professionnel
                    </a>
                </div>

            </div>

            <hr class="border-light opacity-25 my-4">

            <div class="text-center small">
                © {{ date('Y') }} PharmaFind — Tous droits réservés.
            </div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Referme le menu mobile après un clic sur un lien d'ancre (#services, #faq)
        document.addEventListener('DOMContentLoaded', function () {
            const menu = document.getElementById('citizenNavbar');
            if (!menu) return;

            menu.querySelectorAll('a[href*="#"]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (menu.classList.contains('show')) {
                        bootstrap.Collapse.getOrCreateInstance(menu).hide();
                    }
                });
            });
        });
    </script>

    <script>
        // Allume « Fonctionnalités » / « FAQ » dans la navbar selon la section visible
        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.navbar-nav .nav-link[data-section]');
            const home = document.querySelector('.navbar-nav .nav-link[aria-current="page"]');
            const sections = [];

            links.forEach(function (link) {
                const section = document.getElementById(link.dataset.section);
                if (section) sections.push({ link: link, section: section });
            });

            if (!sections.length) return;

            function update() {
                let current = null;

                sections.forEach(function (item) {
                    const rect = item.section.getBoundingClientRect();
                    if (rect.top <= 140 && rect.bottom > 140) current = item;
                });

                links.forEach(function (link) { link.classList.remove('active'); });

                if (current) {
                    current.link.classList.add('active');
                    if (home) home.classList.remove('active');
                } else if (home) {
                    home.classList.add('active');
                }
            }

            window.addEventListener('scroll', update, { passive: true });
            update();
        });
    </script>

    @stack('scripts')

</body>
</html>