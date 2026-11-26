
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

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: var(--text);
        }

        .navbar {
            background: white;
            padding: 17px 0;
            box-shadow: 0 3px 20px rgba(18,59,74,.05);
        }

        .brand {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand span { color: var(--teal); }

        .btn-outline-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 15px;
            border: 1px solid #DCE7EB;
            border-radius: 9px;
            color: var(--navy);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-outline-custom:hover {
            background: #F1F8F8;
            border-color: var(--teal);
            color: var(--teal);
        }

        .results-header {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--dark-navy), var(--navy) 65%, #17646A);
            color: white;
            padding: 55px 0 85px;
        }

        .results-header::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(22,166,160,.12);
            right: -80px;
            top: -160px;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 30px;
            color: #B9F0E8;
            background: rgba(255,255,255,.07);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 17px;
        }

        .results-header h1 {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .results-header p {
            color: #D0E0E5;
            font-size: 14px;
            line-height: 1.8;
        }

        .results-container {
            position: relative;
            z-index: 2;
            margin-top: -42px;
            padding-bottom: 65px;
        }

        .search-summary {
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 15px;
            padding: 22px 25px;
            box-shadow: 0 10px 30px rgba(18,59,74,.05);
        }

        .summary-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 5px;
        }

        .summary-value {
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .summary-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: #EAF8F6;
            color: var(--teal);
            font-size: 19px;
        }

        .notice {
            border-radius: 11px;
            padding: 14px 17px;
            font-size: 12px;
            line-height: 1.8;
        }

        .notice-info {
            background: #EAF5FA;
            color: #315B70;
            border: 1px solid #D4E8F0;
        }

        .notice-warning {
            background: #FFF8E7;
            color: #80621D;
            border: 1px solid #F2E3B7;
        }

        .section-title {
            color: var(--navy);
            font-size: 19px;
            font-weight: 800;
        }

        .result-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 13px;
            border-radius: 30px;
            background: #E8F7F4;
            color: #087F78;
            font-size: 12px;
            font-weight: 800;
        }

        .result-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 17px;
            padding: 23px;
            transition: .25s;
        }

        .result-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(18,59,74,.08);
        }

        .pharmacy-icon {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: #EAF8F6;
            color: var(--teal);
            font-size: 21px;
        }

        .pharmacy-name {
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .pharmacy-type {
            display: inline-block;
            background: #EFF7F8;
            color: #315B70;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            margin-top: 7px;
        }

        .detail-list {
            margin-top: 20px;
        }

        .detail {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #687B85;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 10px;
            overflow-wrap: anywhere;
        }

        .detail i {
            color: var(--teal);
            font-size: 14px;
            margin-top: 2px;
        }

        .distance {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #EAF8F6;
            color: #087F78;
            padding: 7px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            margin-top: 2px;
        }

        .stock-box {
            background: #F5FAFA;
            border: 1px solid #E0EFED;
            border-radius: 12px;
            padding: 15px;
            margin-top: 18px;
        }

        .stock-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .stock-title i {
            color: var(--teal);
            font-size: 16px;
        }

        .stock-item {
            border-top: 1px solid #DDEBE9;
            padding-top: 11px;
            margin-top: 11px;
        }

        .stock-item:first-of-type {
            border-top: 0;
            padding-top: 0;
            margin-top: 0;
        }

        .stock-medicine {
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
            line-height: 1.6;
        }

        .stock-quantity {
            color: #128078;
            font-size: 12px;
            font-weight: 800;
            margin-top: 5px;
        }

        .stock-date {
            color: #71858C;
            font-size: 10px;
            margin-top: 5px;
            line-height: 1.6;
        }

        .card-actions {
            margin-top: auto;
            padding-top: 20px;
        }

        .btn-map {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 46px;
            background: var(--teal);
            color: white;
            text-decoration: none;
            padding: 11px 15px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
        }

        .btn-map:hover {
            background: #108D88;
            color: white;
            transform: translateY(-1px);
        }

        .empty-state {
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 17px;
            padding: 50px 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(18,59,74,.04);
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            border-radius: 20px;
            background: #EAF8F6;
            color: var(--teal);
            font-size: 30px;
        }

        .empty-state h3 {
            color: var(--navy);
            font-size: 19px;
            font-weight: 800;
        }

        .empty-state p {
            max-width: 560px;
            margin: 10px auto 20px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .btn-new-search {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 45px;
            padding: 10px 18px;
            border-radius: 9px;
            background: var(--teal);
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-new-search:hover {
            background: #108D88;
            color: white;
        }

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link { color: var(--navy); }

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

        @media(max-width:768px) {
            .results-header { padding: 45px 0 75px; }
            .search-summary { padding: 18px; }
            .result-card { padding: 19px; }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="brand" href="{{ route('citoyen.home') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <a href="{{ route('citoyen.search.form') }}" class="btn-outline-custom">
            <i class="bi bi-search me-2"></i>
            Nouvelle recherche
        </a>
    </div>
</nav>

<header class="results-header">
    <div class="container header-content">
        <div class="header-label">
            <i class="bi bi-check-circle-fill"></i>
            Recherche terminée
        </div>

        <h1>Résultats de recherche</h1>

        <p class="mb-0">
            Consultez les pharmacies correspondant à votre médicament
            et à votre localisation.
        </p>
    </div>
</header>

<main class="container results-container">

    <div class="notice notice-info mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        Les quantités affichées correspondent aux stocks déclarés par les pharmacies.
        Elles ne garantissent pas la disponibilité réelle au moment de votre visite.
    </div>

    <section class="search-summary mb-4">
        <div class="row g-3 align-items-center">

            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="summary-icon">
                        <i class="bi bi-capsule"></i>
                    </div>

                    <div>
                        <div class="summary-label">Médicament recherché</div>
                        <div class="summary-value">
                            {{ $search['medicament'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="summary-icon">
                        <i class="bi bi-shop"></i>
                    </div>

                    <div>
                        <div class="summary-label">Type de pharmacie</div>
                        <div class="summary-value">
                            {{ $search['type'] === 'jour' ? 'Pharmacies de jour' : 'Pharmacies de nuit' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="summary-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <div>
                        <div class="summary-label">Localisation</div>
                        <div class="summary-value">
                            @if($hasLocation)
                                Position GPS
                            @elseif(!empty($search['ville']))
                                {{ $search['ville'] }}
                            @elseif(!empty($search['gouvernorat']))
                                {{ preg_replace('/^Gouvernorat\s+/u', '', $search['gouvernorat']) }}
                            @else
                                Toutes les zones
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @if(!$medicamentFound)

        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-capsule"></i>
            </div>

            <h3>Médicament introuvable</h3>

            <p>
                Aucun médicament correspondant à
                « {{ $search['medicament'] }} »
                n'a été trouvé dans notre catalogue.
                Vérifiez l'orthographe ou essayez un autre nom.
            </p>

            <a href="{{ route('citoyen.search.form') }}" class="btn-new-search">
                <i class="bi bi-search"></i>
                Effectuer une nouvelle recherche
            </a>
        </div>

    @elseif($pharmacies->isEmpty())

        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-shop"></i>
            </div>

            <h3>Aucune pharmacie correspondante</h3>

            <p>
                Le médicament existe dans notre catalogue, mais aucune pharmacie
                approuvée de type
                {{ $search['type'] === 'jour' ? 'jour' : 'nuit' }}
                ne déclare actuellement ce médicament en stock
                dans la zone sélectionnée.
            </p>

            <a href="{{ route('citoyen.search.form') }}" class="btn-new-search">
                <i class="bi bi-search"></i>
                Modifier ma recherche
            </a>
        </div>

    @else

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="section-title mb-1">
                    Pharmacies correspondantes
                </h2>

                <p class="text-muted small mb-0">
                    Consultez les informations et les stocks déclarés.
                </p>
            </div>

            <span class="result-count">
                <i class="bi bi-check-circle-fill"></i>
                {{ $pharmacies->total() }} résultat(s)
            </span>
        </div>

        <div class="row g-4">

            @foreach($pharmacies as $pharmacy)

                <div class="col-12 col-md-6 col-lg-4">
                    <article class="result-card">

                        <div class="d-flex align-items-start gap-3">
                            <div class="pharmacy-icon">
                                <i class="bi bi-hospital"></i>
                            </div>

                            <div>
                                <h3 class="pharmacy-name mb-0">
                                    {{ $pharmacy->name }}
                                </h3>

                                <span class="pharmacy-type">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $pharmacy->type === 'jour' ? 'Pharmacie de jour' : 'Pharmacie de nuit' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-list">

                            <div class="detail">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>
                                    {{ $pharmacy->address ?: 'Adresse non renseignée' }}
                                </span>
                            </div>

                            @if($pharmacy->city)
                                <div class="detail">
                                    <i class="bi bi-building"></i>
                                    <span>{{ $pharmacy->city }}</span>
                                </div>
                            @endif

                            @if($pharmacy->region)
                                <div class="detail">
                                    <i class="bi bi-map"></i>
                                    <span>{{ $pharmacy->region }}</span>
                                </div>
                            @endif

                            @if($pharmacy->phone)
                                <div class="detail">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>{{ $pharmacy->phone }}</span>
                                </div>
                            @endif

                            @if($hasLocation && isset($pharmacy->distance))
                                <div>
                                    <span class="distance">
                                        <i class="bi bi-signpost"></i>
                                        {{ number_format($pharmacy->distance, 2, ',', ' ') }}
                                        km de votre position
                                    </span>
                                </div>
                            @endif

                        </div>

                        <div class="stock-box">

                            <div class="stock-title">
                                <i class="bi bi-capsule"></i>
                                Médicaments déclarés en stock
                            </div>

                            @foreach($pharmacy->stocks as $stock)

                                <div class="stock-item">

                                    <div class="stock-medicine">
                                        {{ $stock->medicament->name ?? 'Médicament' }}
                                    </div>

                                    <div class="stock-quantity">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Quantité déclarée :
                                        {{ $stock->declared_quantity }}
                                    </div>

                                    <div class="stock-date">
                                        <i class="bi bi-clock me-1"></i>
                                        Dernière mise à jour :

                                        @if($stock->updated_stock_at)
                                            {{ $stock->updated_stock_at->format('d/m/Y à H:i') }}
                                        @else
                                            Date non renseignée
                                        @endif
                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <div class="card-actions">

                            @if($pharmacy->latitude !== null && $pharmacy->longitude !== null)

                                <a
                                    href="https://www.google.com/maps/dir/?api=1&destination={{ $pharmacy->latitude }},{{ $pharmacy->longitude }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-map"
                                >
                                    <i class="bi bi-sign-turn-right-fill"></i>
                                    Obtenir l'itinéraire
                                </a>

                            @elseif($pharmacy->maps_url)

                                <a
                                    href="{{ $pharmacy->maps_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn-map"
                                >
                                    <i class="bi bi-map"></i>
                                    Localiser la pharmacie
                                </a>

                            @else

                                <span class="text-muted small">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Localisation non disponible
                                </span>

                            @endif

                        </div>

                    </article>
                </div>

            @endforeach

        </div>

        {{ $pharmacies->links('pagination::bootstrap-5') }}

    @endif

    <div class="notice notice-warning mt-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Information importante :</strong>
        les stocks sont déclarés par les pharmacies et peuvent évoluer.
        Contactez la pharmacie avant de vous déplacer pour confirmer la disponibilité.
    </div>

</main>

<footer>
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
        <a class="brand" href="{{ route('citoyen.home') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <span>
            &copy; {{ date('Y') }} PharmaFind. Tous droits réservés.
        </span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
