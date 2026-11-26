
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123B4A">

    <title>PharmaFind | Recherche de médicament</title>

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
            color: var(--text);
            background: var(--light-bg);
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

        .navbar .nav-link {
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 10px;
            min-height: 50px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: .25s;
        }

        .btn-primary-custom:hover {
            background: #108D88;
            color: white;
            transform: translateY(-2px);
        }

        .page-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--dark-navy), var(--navy) 65%, #17646A);
            color: white;
            padding: 65px 0 105px;
        }

        .page-hero::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(22,166,160,.12);
            top: -170px;
            right: -80px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
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
            margin-bottom: 20px;
        }

        .page-hero h1 {
            font-size: clamp(30px, 4vw, 45px);
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .page-hero p {
            max-width: 650px;
            color: #D0E0E5;
            line-height: 1.8;
            font-size: 14px;
        }

        .search-wrapper {
            position: relative;
            z-index: 2;
            margin-top: -55px;
            padding-bottom: 75px;
        }

        .search-card {
            background: white;
            border: 1px solid #EAF0F3;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 18px 50px rgba(18,59,74,.09);
        }

        .section-heading {
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .field-label {
            display: block;
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .form-control,
        .form-select {
            min-height: 49px;
            border: 1px solid #DDE7EB;
            border-radius: 10px;
            color: var(--text);
            font-size: 13px;
            padding: 11px 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(22,166,160,.12);
        }

        .input-icon {
            position: relative;
        }

        .input-icon > i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--teal);
            z-index: 2;
        }

        .input-icon .form-control {
            padding-left: 43px;
        }

        .required { color: #D9534F; }

        .type-option {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #E1EAEE;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: .2s;
            height: 100%;
        }

        .type-option:hover {
            border-color: var(--teal);
            background: #F5FBFA;
        }

        .type-option:has(input:checked) {
            border-color: var(--teal);
            background: #EFFAF8;
            box-shadow: 0 0 0 2px rgba(22,166,160,.08);
        }

        .type-option input {
            accent-color: var(--teal);
            width: 17px;
            height: 17px;
        }

        .type-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E9F8F6;
            color: var(--teal);
            border-radius: 11px;
            font-size: 20px;
        }

        .type-name {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
        }

        .type-description {
            color: var(--muted);
            font-size: 11px;
            margin-top: 3px;
        }

        .location-panel {
            background: #F8FAFB;
            border: 1px solid #EAF0F3;
            border-radius: 15px;
            padding: 22px;
        }

        .gps-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 48px;
            border: 1px solid #B8E5E0;
            border-radius: 10px;
            background: #EFFAF8;
            color: #087F78;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .gps-button:hover {
            background: #DDF5F1;
        }

        .gps-button:disabled {
            opacity: .65;
            cursor: wait;
        }

        .gps-status {
            font-size: 12px;
            margin-top: 9px;
            line-height: 1.6;
        }

        .separator {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            margin: 22px 0;
        }

        .separator::before,
        .separator::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #E1EAEE;
        }

        .submit-button {
            width: 100%;
            min-height: 54px;
            font-size: 14px;
        }

        .form-note {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        footer {
            background: var(--dark-navy);
            color: #B9C9CF;
            padding: 30px 0;
            font-size: 12px;
        }

        footer .brand { color: white; }

        @media(max-width:768px) {
            .page-hero { padding: 48px 0 85px; }
            .search-card { padding: 23px 18px; }
            .search-wrapper { margin-top: -45px; }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="brand" href="{{ route('citoyen.home') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <a href="{{ route('citoyen.home') }}"
           class="nav-link">
            <i class="bi bi-arrow-left me-1"></i>
            Retour à l'accueil
        </a>
    </div>
</nav>

<header class="page-hero">
    <div class="container hero-content">
        <div class="hero-label">
            <i class="bi bi-search"></i>
            Recherche de médicaments
        </div>

        <h1>Trouvez votre médicament<br>près de vous.</h1>

        <p class="mb-0 mt-3">
            Indiquez le médicament recherché, choisissez le type
            de pharmacie et précisez votre localisation pour
            consulter les pharmacies correspondantes.
        </p>
    </div>
</header>

<main class="search-wrapper">
    <div class="container">

        <div class="search-card">

            <div class="mb-4">
                <h2 class="section-heading mb-2">
                    <i class="bi bi-capsule me-2" style="color:#16A6A0;"></i>
                    Informations de recherche
                </h2>

                <p class="section-subtitle mb-0">
                    Les champs marqués d'un astérisque sont obligatoires.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('citoyen.search.results') }}"
                  id="searchForm">

                <div class="mb-4">
                    <label for="medicament" class="field-label">
                        Nom du médicament <span class="required">*</span>
                    </label>

                    <div class="input-icon">
                        <i class="bi bi-capsule"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="medicament"
                            name="medicament"
                            placeholder="Exemple : Paracétamol"
                            value="{{ old('medicament') }}"
                            maxlength="150"
                            required
                        >
                    </div>

                    @error('medicament')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="field-label">
                        Type de pharmacie <span class="required">*</span>
                    </label>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="type-option">
                                <input
                                    type="radio"
                                    name="type"
                                    value="jour"
                                    required
                                    {{ old('type') === 'jour' ? 'checked' : '' }}
                                >

                                <span class="type-icon">
                                    <i class="bi bi-sun-fill"></i>
                                </span>

                                <span>
                                    <span class="type-name d-block">Pharmacie de jour</span>
                                    <span class="type-description d-block">
                                        Recherche de pharmacies de jour
                                    </span>
                                </span>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label class="type-option">
                                <input
                                    type="radio"
                                    name="type"
                                    value="nuit"
                                    required
                                    {{ old('type') === 'nuit' ? 'checked' : '' }}
                                >

                                <span class="type-icon">
                                    <i class="bi bi-moon-stars-fill"></i>
                                </span>

                                <span>
                                    <span class="type-name d-block">Pharmacie de nuit</span>
                                    <span class="type-description d-block">
                                        Recherche de pharmacies de nuit
                                    </span>
                                </span>
                            </label>
                        </div>

                    </div>

                    @error('type')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <h3 class="section-heading mb-2">
                        <i class="bi bi-geo-alt me-2" style="color:#16A6A0;"></i>
                        Votre localisation
                    </h3>

                    <p class="section-subtitle">
                        Utilisez le GPS ou choisissez une localisation
                        dans les listes proposées.
                    </p>

                    <div class="location-panel">

                        <button
                            type="button"
                            class="gps-button"
                            id="gpsButton"
                        >
                            <i class="bi bi-crosshair"></i>
                            Utiliser ma position GPS
                        </button>

                        <div id="gpsStatus" class="gps-status text-muted">
                            Aucune position GPS sélectionnée.
                        </div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="separator">
                            OU CHOISIR UNE LOCALISATION
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="gouvernorat" class="field-label">
                                    Gouvernorat
                                </label>

                                <select
                                    class="form-select"
                                    name="gouvernorat"
                                    id="gouvernorat"
                                >
                                    <option value="">Tous les gouvernorats</option>

                                    @foreach($gouvernorats as $gouvernorat)
                                        <option
                                            value="{{ $gouvernorat }}"
                                            {{ old('gouvernorat') === $gouvernorat ? 'selected' : '' }}
                                        >
                                            {{ preg_replace('/^Gouvernorat\s+/u', '', $gouvernorat) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('gouvernorat')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ville" class="field-label">
                                    Ville / localité
                                </label>

                                <select
                                    class="form-select"
                                    name="ville"
                                    id="ville"
                                    disabled
                                >
                                    <option value="">
                                        Choisissez d'abord un gouvernorat
                                    </option>
                                </select>

                                @error('ville')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div id="formError" class="alert alert-danger d-none"></div>

                <button type="submit" class="btn-primary-custom submit-button">
                    <i class="bi bi-search me-2"></i>
                    Rechercher les pharmacies
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <p class="form-note text-center mt-3 mb-0">
                    Les informations de stock affichées sont déclarées
                    par les pharmacies et doivent être confirmées auprès d'elles.
                </p>

            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container d-flex justify-content-between flex-wrap gap-2">
        <a class="brand" href="{{ route('citoyen.home') }}">
            <i class="bi bi-heart-pulse-fill"></i>
            Pharma<span>Find</span>
        </a>

        <span>
            &copy; {{ date('Y') }} PharmaFind. Tous droits réservés.
        </span>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const governorateSelect = document.getElementById('gouvernorat');
    const localitySelect = document.getElementById('ville');
    const gpsButton = document.getElementById('gpsButton');
    const gpsStatus = document.getElementById('gpsStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const formError = document.getElementById('formError');

    const localitiesUrl = @json(route('citoyen.search.localities'));

    async function loadLocalities(governorate, selectedLocality = '') {

        localitySelect.innerHTML = '';

        if (!governorate) {
            localitySelect.disabled = true;
            localitySelect.add(new Option(
                'Choisissez d’abord un gouvernorat',
                ''
            ));
            return;
        }

        localitySelect.disabled = true;
        localitySelect.add(new Option('Chargement...', ''));

        try {
            const url = new URL(localitiesUrl, window.location.origin);
            url.searchParams.set('gouvernorat', governorate);

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error('Erreur de chargement');
            }

            const localities = await response.json();

            localitySelect.innerHTML = '';

            localitySelect.add(new Option(
                'Toutes les villes / localités',
                ''
            ));

            localities.forEach(function (locality) {
                const option = new Option(locality, locality);

                if (locality === selectedLocality) {
                    option.selected = true;
                }

                localitySelect.add(option);
            });

            localitySelect.disabled = localities.length === 0;

            if (localities.length === 0) {
                localitySelect.innerHTML = '';
                localitySelect.add(new Option(
                    'Aucune localité disponible',
                    ''
                ));
            }

        } catch (error) {
            localitySelect.innerHTML = '';
            localitySelect.add(new Option(
                'Impossible de charger les localités',
                ''
            ));
            localitySelect.disabled = true;
        }
    }

    governorateSelect.addEventListener('change', function () {
        loadLocalities(this.value);
    });

    if (governorateSelect.value) {
        loadLocalities(
            governorateSelect.value,
            @json(old('ville', ''))
        );
    }

    gpsButton.addEventListener('click', function () {

        formError.classList.add('d-none');

        if (!navigator.geolocation) {
            gpsStatus.textContent =
                'La géolocalisation n’est pas disponible sur ce navigateur.';
            gpsStatus.className = 'gps-status text-danger';
            return;
        }

        gpsButton.disabled = true;
        gpsButton.innerHTML =
            '<i class="bi bi-hourglass-split"></i> Localisation en cours...';

        gpsStatus.textContent =
            'Veuillez autoriser l’accès à votre position.';
        gpsStatus.className = 'gps-status text-muted';

        navigator.geolocation.getCurrentPosition(

            function (position) {

                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;

                gpsStatus.textContent =
                    'Votre position GPS a été enregistrée.';
                gpsStatus.className = 'gps-status text-success';

                gpsButton.disabled = false;
                gpsButton.innerHTML =
                    '<i class="bi bi-check-circle-fill"></i> Position GPS enregistrée';
            },

            function (error) {

                let message = 'Impossible de récupérer votre position.';

                if (error.code === error.PERMISSION_DENIED) {
                    message = 'Vous avez refusé l’accès à votre position.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message = 'Votre position est indisponible.';
                } else if (error.code === error.TIMEOUT) {
                    message = 'La demande de localisation a expiré.';
                }

                gpsStatus.textContent = message;
                gpsStatus.className = 'gps-status text-danger';

                gpsButton.disabled = false;
                gpsButton.innerHTML =
                    '<i class="bi bi-crosshair"></i> Réessayer la géolocalisation';
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

});
</script>

</body>
</html>
