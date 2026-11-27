@extends('citoyen.layout')

@section('title', 'Recherche de médicament')
@section('meta_description', 'Recherchez un médicament et trouvez les pharmacies correspondantes selon votre localisation en Tunisie.')

@push('styles')
<style>
    .pf-search-page {
        --pf-navy: #123B4A;
        --pf-dark: #0D303D;
        --pf-teal: #16A6A0;
        --pf-light: #F5F8FA;
        --pf-muted: #687C85;
        color: var(--pf-navy);
        background: var(--pf-light);
        min-height: 100vh;
    }

    .pf-search-page * { box-sizing: border-box; }

    .pf-search-hero {
        position: relative;
        overflow: hidden;
        padding: 75px 0 115px;
        color: white;
        background: linear-gradient(120deg, #0D303D, #123B4A 60%, #17646A);
    }

    .pf-search-hero::before,
    .pf-search-hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(22,166,160,.12);
        pointer-events: none;
    }

    .pf-search-hero::before {
        width: 360px;
        height: 360px;
        top: -190px;
        right: -70px;
    }

    .pf-search-hero::after {
        width: 230px;
        height: 230px;
        bottom: -160px;
        left: 12%;
    }

    .pf-search-hero-content {
        position: relative;
        z-index: 1;
    }

    .pf-search-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 9px 16px;
        margin-bottom: 22px;
        border-radius: 30px;
        background: rgba(22,166,160,.18);
        border: 1px solid rgba(255,255,255,.2);
        color: #B8F2EC;
        font-size: 12px;
        font-weight: 700;
    }

    .pf-search-hero h1 {
        max-width: 700px;
        font-size: clamp(34px, 5vw, 50px);
        line-height: 1.15;
        letter-spacing: -1.5px;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .pf-search-hero h1 span { color: #52D3C5; }

    .pf-search-hero p {
        max-width: 650px;
        color: #DCE9EC;
        font-size: 15px;
        line-height: 1.9;
        margin: 0;
    }

    .pf-search-main {
        position: relative;
        z-index: 2;
        margin-top: -55px;
        padding-bottom: 85px;
    }

    .pf-search-card {
        padding: 38px;
        background: white;
        border: 1px solid #E8EFF1;
        border-radius: 19px;
        box-shadow: 0 18px 50px rgba(13,48,61,.09);
    }

    .pf-search-heading {
        color: var(--pf-navy);
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 9px;
    }

    .pf-search-subtitle {
        color: var(--pf-muted);
        font-size: 13px;
        line-height: 1.8;
    }

    .pf-search-label {
        display: block;
        color: var(--pf-navy);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .pf-required { color: #D9534F; }

    .pf-search-page .form-control,
    .pf-search-page .form-select {
        min-height: 50px;
        border: 1px solid #DDE7EB;
        border-radius: 10px;
        color: #243746;
        font-size: 13px;
        padding: 12px 14px;
        background-color: white;
    }

    .pf-search-page .form-control:focus,
    .pf-search-page .form-select:focus {
        border-color: var(--pf-teal);
        box-shadow: 0 0 0 3px rgba(22,166,160,.12);
    }

    .pf-search-input-icon { position: relative; }

    .pf-search-input-icon > i {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: var(--pf-teal);
        z-index: 2;
    }

    .pf-search-input-icon .form-control { padding-left: 43px; }

    .pf-type-option {
        display: flex;
        align-items: center;
        gap: 13px;
        height: 100%;
        padding: 17px;
        border: 1px solid #E1EAEE;
        border-radius: 13px;
        cursor: pointer;
        transition: .2s;
    }

    .pf-type-option:hover {
        border-color: var(--pf-teal);
        background: #F5FBFA;
    }

    .pf-type-option:has(input:checked) {
        border-color: var(--pf-teal);
        background: #EFFAF8;
        box-shadow: 0 0 0 2px rgba(22,166,160,.08);
    }

    .pf-type-option input {
        width: 17px;
        height: 17px;
        accent-color: var(--pf-teal);
        flex-shrink: 0;
    }

    .pf-type-icon {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #E9F8F6;
        color: var(--pf-teal);
        font-size: 20px;
    }

    .pf-type-name {
        color: var(--pf-navy);
        font-size: 13px;
        font-weight: 800;
    }

    .pf-type-description {
        display: block;
        color: var(--pf-muted);
        font-size: 11px;
        margin-top: 4px;
    }

    .pf-location-panel {
        padding: 24px;
        background: #F8FAFB;
        border: 1px solid #E8EFF1;
        border-radius: 15px;
    }

    .pf-gps-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 49px;
        padding: 12px 18px;
        border: 1px solid #B8E5E0;
        border-radius: 10px;
        background: #EFFAF8;
        color: #087F78;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
    }

    .pf-gps-button:hover {
        background: #DDF5F1;
    }

    .pf-gps-button:disabled {
        opacity: .65;
        cursor: wait;
    }

    .pf-gps-status {
        min-height: 20px;
        margin-top: 10px;
        font-size: 12px;
        line-height: 1.6;
    }

    .pf-search-separator {
        display: flex;
        align-items: center;
        gap: 13px;
        margin: 23px 0;
        color: var(--pf-muted);
        font-size: 11px;
        font-weight: 700;
    }

    .pf-search-separator::before,
    .pf-search-separator::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #E1EAEE;
    }

    .pf-search-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        min-height: 55px;
        border: none;
        border-radius: 10px;
        background: var(--pf-teal);
        color: white;
        font-size: 14px;
        font-weight: 800;
        transition: .25s;
    }

    .pf-search-submit:hover {
        background: #108D88;
        color: white;
        transform: translateY(-2px);
    }

    .pf-search-note {
        color: var(--pf-muted);
        font-size: 11px;
        line-height: 1.8;
    }

    .pf-search-info {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 17px;
        margin-top: 25px;
        border-radius: 12px;
        background: #EFFAF8;
        color: #356A6A;
        font-size: 12px;
        line-height: 1.7;
    }

    .pf-search-info i {
        color: var(--pf-teal);
        font-size: 18px;
    }

    @media (max-width: 767px) {
        .pf-search-hero { padding: 55px 0 90px; }
        .pf-search-main { margin-top: -42px; padding-bottom: 55px; }
        .pf-search-card { padding: 25px 19px; }
        .pf-location-panel { padding: 18px; }
    }
</style>
@endpush

@section('content')

<div class="pf-search-page">

    {{-- HERO --}}
    <section class="pf-search-hero">
        <div class="container pf-search-hero-content">

            <div class="pf-search-eyebrow">
                <i class="bi bi-search"></i>
                Recherche de médicaments
            </div>

            <h1>
                Trouvez votre médicament
                <span>près de vous.</span>
            </h1>

            <p>
                Recherchez un médicament, choisissez le type de pharmacie
                et indiquez votre localisation pour consulter les pharmacies
                correspondantes en Tunisie.
            </p>

        </div>
    </section>

    {{-- FORMULAIRE --}}
    <main class="pf-search-main">
        <div class="container">

            <div class="pf-search-card">

                <div class="mb-4">
                    <h2 class="pf-search-heading">
                        <i class="bi bi-capsule me-2" style="color:#16A6A0;"></i>
                        Informations de recherche
                    </h2>

                    <p class="pf-search-subtitle mb-0">
                        Complétez les informations ci-dessous pour lancer votre recherche.
                        Les champs marqués d'un astérisque sont obligatoires.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('citoyen.search.results') }}"
                      id="searchForm">

                    {{-- MEDICAMENT --}}
                    <div class="mb-4">
                        <label for="medicament" class="pf-search-label">
                            Nom du médicament <span class="pf-required">*</span>
                        </label>

                        <div class="pf-search-input-icon">
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

                    {{-- TYPE DE PHARMACIE --}}
                    <div class="mb-4">
                        <label class="pf-search-label">
                            Type de pharmacie <span class="pf-required">*</span>
                        </label>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="pf-type-option">
                                    <input
                                        type="radio"
                                        name="type"
                                        value="jour"
                                        required
                                        {{ old('type') === 'jour' ? 'checked' : '' }}
                                    >

                                    <span class="pf-type-icon">
                                        <i class="bi bi-sun-fill"></i>
                                    </span>

                                    <span>
                                        <span class="pf-type-name">Pharmacie de jour</span>
                                        <span class="pf-type-description">
                                            Recherche de pharmacies de jour
                                        </span>
                                    </span>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label class="pf-type-option">
                                    <input
                                        type="radio"
                                        name="type"
                                        value="nuit"
                                        required
                                        {{ old('type') === 'nuit' ? 'checked' : '' }}
                                    >

                                    <span class="pf-type-icon">
                                        <i class="bi bi-moon-stars-fill"></i>
                                    </span>

                                    <span>
                                        <span class="pf-type-name">Pharmacie de nuit</span>
                                        <span class="pf-type-description">
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

                    {{-- LOCALISATION --}}
                    <div class="mb-4">

                        <h3 class="pf-search-heading mb-2">
                            <i class="bi bi-geo-alt me-2" style="color:#16A6A0;"></i>
                            Votre localisation
                        </h3>

                        <p class="pf-search-subtitle">
                            Utilisez votre position GPS ou sélectionnez
                            un gouvernorat et une localité.
                        </p>

                        <div class="mb-3">
                            <label class="pf-search-label">
                                Comment souhaitez-vous rechercher les pharmacies ?
                            </label>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="pf-type-option">
                                        <input
                                            type="radio"
                                            name="mode"
                                            value="gps"
                                            {{ old('mode', 'manual') === 'gps' ? 'checked' : '' }}
                                        >
                                        <span class="pf-type-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </span>
                                        <span>
                                            <span class="pf-type-name">Selon ma position GPS</span>
                                            <span class="pf-type-description">
                                                Trouver les pharmacies les plus proches.
                                            </span>
                                        </span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label class="pf-type-option">
                                        <input
                                            type="radio"
                                            name="mode"
                                            value="manual"
                                            {{ old('mode', 'manual') === 'manual' ? 'checked' : '' }}
                                        >
                                        <span class="pf-type-icon">
                                            <i class="bi bi-map-fill"></i>
                                        </span>
                                        <span>
                                            <span class="pf-type-name">Selon un gouvernorat</span>
                                            <span class="pf-type-description">
                                                Choisir un gouvernorat et une ville.
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @error('mode')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="pf-location-panel">

                            <button
                                type="button"
                                class="pf-gps-button"
                                id="gpsButton"
                            >
                                <i class="bi bi-crosshair"></i>
                                Utiliser ma position GPS
                            </button>

                            <div id="gpsStatus" class="pf-gps-status text-muted">
                                Aucune position GPS sélectionnée.
                            </div>

                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <div class="pf-search-separator">
                                OU CHOISIR UNE LOCALISATION
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="gouvernorat" class="pf-search-label">
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
                                    <label for="ville" class="pf-search-label">
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

                    {{-- BOUTON --}}
                    <button type="submit" class="pf-search-submit">
                        <i class="bi bi-search"></i>
                        Rechercher les pharmacies
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <p class="pf-search-note text-center mt-3 mb-0">
                        Les informations de disponibilité sont déclarées par les pharmacies
                        et peuvent évoluer. Pensez à confirmer auprès de la pharmacie avant
                        de vous déplacer.
                    </p>

                </form>

                <div class="pf-search-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>
                        La géolocalisation nécessite votre autorisation et fonctionne
                        généralement lorsque le site est accessible en HTTPS ou sur localhost.
                    </span>
                </div>

            </div>
        </div>
    </main>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('searchForm');
    const governorateSelect = document.getElementById('gouvernorat');
    const localitySelect = document.getElementById('ville');
    const gpsButton = document.getElementById('gpsButton');
    const gpsStatus = document.getElementById('gpsStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const modeInputs = document.querySelectorAll('input[name="mode"]');

    const localitiesUrl = @json(route('citoyen.search.localities'));
    const oldLocality = @json(old('ville', ''));

    const MSG_CHOOSE_GOV = 'Choisissez d\u2019abord un gouvernorat';

    function selectedMode() {
        return document.querySelector('input[name="mode"]:checked')?.value || 'manual';
    }

    function resetLocalitySelect() {
        localitySelect.innerHTML = '';
        localitySelect.add(new Option(MSG_CHOOSE_GOV, ''));
        localitySelect.disabled = true;
    }

    async function loadLocalities(governorate, selectedLocality = '') {
        localitySelect.innerHTML = '';

        if (!governorate) {
            resetLocalitySelect();
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

            if (localities.length === 0) {
                localitySelect.add(new Option('Aucune localité disponible', ''));
                localitySelect.disabled = true;
                return;
            }

            localitySelect.add(new Option('Toutes les villes / localités', ''));

            localities.forEach(function (locality) {
                const option = new Option(locality, locality);
                if (locality === selectedLocality) {
                    option.selected = true;
                }
                localitySelect.add(option);
            });

            localitySelect.disabled = selectedMode() !== 'manual';
        } catch (error) {
            localitySelect.innerHTML = '';
            localitySelect.add(new Option('Impossible de charger les localités', ''));
            localitySelect.disabled = true;
        }
    }

    function updateLocationMode() {
        const gpsMode = selectedMode() === 'gps';

        // Un seul mode est actif à la fois.
        gpsButton.disabled = !gpsMode;
        governorateSelect.disabled = gpsMode;
        governorateSelect.required = !gpsMode;

        if (gpsMode) {
            // Le mode GPS ne doit jamais envoyer une localisation manuelle.
            governorateSelect.value = '';
            resetLocalitySelect();

            gpsStatus.textContent = latitudeInput.value && longitudeInput.value
                ? 'Votre position GPS a été enregistrée.'
                : 'Cliquez sur le bouton pour récupérer votre position GPS.';
            gpsStatus.className = 'pf-gps-status text-muted';
        } else {
            // Le mode manuel ne doit jamais envoyer d'anciennes coordonnées GPS.
            latitudeInput.value = '';
            longitudeInput.value = '';
            gpsStatus.textContent = 'Le mode de recherche manuelle est sélectionné.';
            gpsStatus.className = 'pf-gps-status text-muted';
            gpsButton.innerHTML = '<i class="bi bi-crosshair"></i> Utiliser ma position GPS';

            if (governorateSelect.value) {
                loadLocalities(governorateSelect.value, oldLocality);
            } else {
                resetLocalitySelect();
            }
        }
    }

    modeInputs.forEach(function (input) {
        input.addEventListener('change', updateLocationMode);
    });

    governorateSelect.addEventListener('change', function () {
        latitudeInput.value = '';
        longitudeInput.value = '';
        loadLocalities(this.value);
    });

    gpsButton.addEventListener('click', function () {
        if (selectedMode() !== 'gps') {
            return;
        }

        if (!navigator.geolocation) {
            gpsStatus.textContent = 'La géolocalisation n\u2019est pas disponible sur ce navigateur.';
            gpsStatus.className = 'pf-gps-status text-danger';
            return;
        }

        gpsButton.disabled = true;
        gpsButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Localisation en cours...';
        gpsStatus.textContent = 'Veuillez autoriser l\u2019accès à votre position.';
        gpsStatus.className = 'pf-gps-status text-muted';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;

                // Effacer les éventuelles valeurs manuelles.
                governorateSelect.value = '';
                resetLocalitySelect();

                gpsStatus.textContent = 'Votre position GPS a été enregistrée.';
                gpsStatus.className = 'pf-gps-status text-success';
                gpsButton.disabled = false;
                gpsButton.innerHTML = '<i class="bi bi-check-circle-fill"></i> Position GPS enregistrée';
            },
            function (error) {
                let message = 'Impossible de récupérer votre position.';

                if (error.code === error.PERMISSION_DENIED) {
                    message = 'Vous avez refusé l\u2019accès à votre position.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message = 'Votre position est indisponible.';
                } else if (error.code === error.TIMEOUT) {
                    message = 'La demande de localisation a expiré.';
                }

                gpsStatus.textContent = message;
                gpsStatus.className = 'pf-gps-status text-danger';
                gpsButton.disabled = false;
                gpsButton.innerHTML = '<i class="bi bi-crosshair"></i> Réessayer la géolocalisation';
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

    form.addEventListener('submit', function (event) {
        if (selectedMode() === 'gps') {
            if (!latitudeInput.value || !longitudeInput.value) {
                event.preventDefault();
                gpsStatus.textContent = 'Veuillez d\u2019abord récupérer votre position GPS.';
                gpsStatus.className = 'pf-gps-status text-danger';
                gpsButton.focus();
            }
        } else if (!governorateSelect.value) {
            event.preventDefault();
            governorateSelect.setCustomValidity('Veuillez choisir un gouvernorat.');
            governorateSelect.reportValidity();
            governorateSelect.addEventListener('change', function clearValidity() {
                governorateSelect.setCustomValidity('');
                governorateSelect.removeEventListener('change', clearValidity);
            });
        }
    });

    // Initialiser le formulaire en respectant le mode sélectionné.
    updateLocationMode();
});
</script>
@endpush