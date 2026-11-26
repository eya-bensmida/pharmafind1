
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
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

        .main-navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(18, 59, 74, 0.04);
            padding: 14px 0;
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

        @media (max-width: 767px) {
            .brand-logo {
                font-size: 20px;
            }

            .main-navbar {
                padding: 10px 0;
            }

            .navbar-nav {
                padding-top: 12px;
            }

            .navbar-nav .nav-link {
                padding: 10px 4px !important;
            }

            .main-footer {
                margin-top: 45px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">

            <a class="brand-logo" href="{{ route('citoyen.home') }}">
                <span class="brand-icon">
                    <i class="bi bi-capsule"></i>
                </span>
                <span>PharmaFind</span>
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#citizenNavbar"
                    aria-controls="citizenNavbar"
                    aria-expanded="false"
                    aria-label="Ouvrir le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="citizenNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('citoyen.home') }}">
                            Accueil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('citoyen.search.form') }}">
                            Rechercher un médicament
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
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

    <main class="page-content">
        @yield('content')
    </main>

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

                    <a href="{{ route('citoyen.search.form') }}" class="footer-link">
                        Recherche
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

    @stack('scripts')

</body>
</html>
