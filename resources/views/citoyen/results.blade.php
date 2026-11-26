
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123B4A">
    <title>PharmaFind | Résultats de recherche</title>

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
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: var(--text);
        }

        .navbar {
            background: white;
            padding: 18px 0;
            box-shadow: 0 3px 20px rgba(18,59,74,.05);
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

        .results-header {
            background: linear-gradient(135deg, var(--dark-navy), #17646A);
            color: white;
            padding: 55px 0 85px;
        }

        .results-header h1 {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
        }

        .results-header p {
            color: #D0E0E5;
        }

        .results-container {
            margin-top: -40px;
            padding-bottom: 70px;
        }

        .result-card {
            height: 100%;
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 16px;
            padding: 25px;
            transition: .25s;
        }

        .result-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(18,59,74,.08);
        }

        .pharmacy-name {
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .detail {
            color: #687B85;
            font-size: 13px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .detail i {
            color: var(--teal);
            margin-right: 7px;
        }

        .btn-map {
            display: inline-block;
            background: var(--teal);
            color: white;
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-map:hover {
            background: #108D88;
            color: white;
        }

        .osm-notice {
            background: #FFF8E7;
            color: #80621D;
            border: 1px solid #F2E3B7;
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 12px;
            line-height: 1.7;
        }

        .empty-state {
            background: white;
            border-radius: 16px;
            padding: 45px 20px;
            text-align: center;
        }

        .empty-state i {
            color: var(--teal);
            font-size: 45px;
        }

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link {
            color: var(--navy);
        }

        .active > .page-link {
            background: var(--teal);
            border-color: var(--teal);
        }

        footer {
            background: var(--dark-navy);
            color: #B9C9CF;
            padding: 30px 0;
            font-size: 12px;
        }

        footer a {
            color: #B9C9CF;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container">
        <a class="brand" href="{{ route('citoyen.home') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <a href="{{ route('citoyen.home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Retour à l'accueil
        </a>
    </div>
</nav>

<header class="results-header">
    <div class="container">
        <h1>Pharmacies trouvées</h1>
        <p class="mb-0">
            Consultez les pharmacies correspondant à votre recherche.
        </p>
    </div>
</header>

<main class="container results-container">

    <div class="osm-notice mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        Les informations proviennent d'OpenStreetMap.
        Elles peuvent être incomplètes ou ne pas être actualisées.
        Leur vérification n'est pas encore garantie.
        © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap contributors</a>.
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1" style="color:#123B4A;">
                Résultats de recherche
            </h2>
            <p class="text-muted small mb-0">
                {{ $pharmacies->total() }} pharmacie(s) trouvée(s)
            </p>
        </div>

        @if($hasLocation)
            <span class="badge rounded-pill text-bg-success p-2">
                <i class="bi bi-geo-alt-fill me-1"></i>
                Recherche géolocalisée
            </span>
        @endif
    </div>

    @if($pharmacies->isEmpty())

        <div class="empty-state shadow-sm">
            <i class="bi bi-search"></i>
            <h3 class="h5 fw-bold mt-3" style="color:#123B4A;">
                Aucune pharmacie trouvée
            </h3>
            <p class="text-muted small">
                Essayez de modifier votre gouvernorat ou votre ville.
            </p>
            <a href="{{ route('citoyen.home') }}" class="btn-map">
                Nouvelle recherche
            </a>
        </div>

    @else

        <div class="row g-4">
            @foreach($pharmacies as $pharmacy)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="result-card">

                        <h3 class="pharmacy-name mb-3">
                            {{ $pharmacy->name }}
                        </h3>

                        <p class="detail mb-2">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $pharmacy->address ?: 'Adresse non renseignée' }}
                        </p>

                        @if($pharmacy->city)
                            <p class="detail mb-2">
                                <i class="bi bi-building"></i>
                                {{ $pharmacy->city }}
                            </p>
                        @endif

                        @if($pharmacy->region)
                            <p class="detail mb-2">
                                <i class="bi bi-map"></i>
                                {{ $pharmacy->region }}
                            </p>
                        @endif

                        @if($pharmacy->phone)
                            <p class="detail mb-2">
                                <i class="bi bi-telephone-fill"></i>
                                {{ $pharmacy->phone }}
                            </p>
                        @endif

                        @if($hasLocation && isset($pharmacy->distance))
                            <p class="detail fw-bold mb-3">
                                <i class="bi bi-signpost"></i>
                                {{ number_format($pharmacy->distance, 2) }} km
                            </p>
                        @endif

                        <div class="mt-4">
                            <a
                                href="https://www.google.com/maps/dir/?api=1&destination={{ $pharmacy->latitude }},{{ $pharmacy->longitude }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-map"
                            >
                                <i class="bi bi-map me-1"></i>
                                Itinéraire
                            </a>
                        </div>

                    </article>
                </div>
            @endforeach
        </div>

        {{ $pharmacies->links('pagination::bootstrap-5') }}

    @endif

    <p class="text-muted small mt-4">
        La disponibilité des médicaments n'est pas encore renseignée dans la base de données.
    </p>

</main>

<footer>
    <div class="container d-flex justify-content-between flex-wrap gap-2">
        <span>PharmaFind — Votre recherche simplifiée.</span>
        <span>&copy; {{ date('Y') }} PharmaFind</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
