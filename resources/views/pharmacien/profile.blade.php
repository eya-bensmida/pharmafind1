@extends('layouts.pharmacien')

@section('title', 'Mon profil - PharmaFind')
@section('topbar', 'Mon profil')

@push('styles')
<style>
    .id-card { overflow: hidden; }

    .id-cover {
        height: 110px;
        background: linear-gradient(125deg, #0D303D 0%, #123B4A 55%, #16A6A0 140%);
        position: relative;
    }

    .id-cover::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -50px;
        top: -80px;
        border-radius: 50%;
        background: rgba(255,255,255,.1);
    }

    .id-avatar {
        width: 86px;
        height: 86px;
        border-radius: 24px;
        margin: -43px auto 0;
        background: linear-gradient(135deg, var(--teal), #3fd0c9);
        border: 5px solid #fff;
        color: #fff;
        font-size: 34px;
        font-weight: 800;
        display: grid;
        place-items: center;
        position: relative;
        z-index: 1;
        box-shadow: 0 10px 24px rgba(22,166,160,.35);
    }

    .info-line {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 11px 0;
        border-bottom: 1px dashed var(--line);
        font-size: 14px;
    }

    .info-line:last-child { border-bottom: none; }
    .info-line i { color: var(--teal); font-size: 17px; margin-top: 1px; }

    .section-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--navy);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 6px 0 16px;
    }

    .section-title .ic {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(22,166,160,.14);
        color: var(--teal);
        display: grid;
        place-items: center;
    }

    .input-ic { position: relative; }

    .input-ic i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .input-ic .form-control, .input-ic .form-select { padding-left: 42px; }

    .type-card { position: relative; }

    .type-card input { position: absolute; opacity: 0; }

    .type-card label {
        display: flex;
        align-items: center;
        gap: 14px;
        border: 2px solid var(--line);
        border-radius: 14px;
        padding: 16px;
        cursor: pointer;
        transition: .2s;
        height: 100%;
        background: #fff;
    }

    .type-card label:hover { border-color: #9fd9d4; }

    .type-card .ic {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: grid;
        place-items: center;
        font-size: 21px;
        background: #F3F7F9;
        color: var(--navy);
        transition: .2s;
    }

    .type-card input:checked + label {
        border-color: var(--teal);
        background: rgba(22,166,160,.07);
        box-shadow: 0 8px 20px rgba(22,166,160,.15);
    }

    .type-card input:checked + label .ic { background: var(--teal); color: #fff; }
    .type-card input:focus-visible + label { outline: 3px solid rgba(22,166,160,.4); }

    .gps-box {
        background: #F8FBFC;
        border: 1px dashed #BFD0D6;
        border-radius: 14px;
        padding: 16px;
    }
</style>
@endpush

@section('content')

@php
    $gouvernorats = [
        'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa', 'Jendouba', 'Kairouan',
        'Kasserine', 'Kébili', 'Le Kef', 'Mahdia', 'Manouba', 'Médenine', 'Monastir', 'Nabeul',
        'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse', 'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan',
    ];

    $currentRegion = old('region', $pharmacy->region);
    if ($currentRegion && ! in_array($currentRegion, $gouvernorats, true)) {
        $gouvernorats[] = $currentRegion;
        sort($gouvernorats);
    }

    $currentType = old('type', $pharmacy->type ?? 'jour');

    $statusMap = [
        'approved' => ['pill-ok', 'bi-patch-check-fill', 'Pharmacie vérifiée'],
        'pending'  => ['pill-low', 'bi-hourglass-split', 'En attente de validation'],
        'rejected' => ['pill-out', 'bi-x-circle-fill', 'Demande refusée'],
    ];
    $status = $statusMap[$pharmacy->verification_status] ?? ['pill-info', 'bi-info-circle', 'Statut inconnu'];

    $stockCount = $pharmacy->stocks()->count();
@endphp

<div class="mb-4 fade-up" style="--d:0">
    <h1 class="page-title">Mon profil</h1>
    <p class="page-subtitle mb-0">Consultez et modifiez les informations de votre pharmacie.</p>
</div>

<div class="row g-4">

    {{-- ===== Carte d'identité ===== --}}
    <div class="col-lg-4">
        <div class="pf-card id-card fade-up" style="--d:1">

            <div class="id-cover"></div>

            <div class="p-4 pt-0 text-center">
                <div class="id-avatar">{{ mb_strtoupper(mb_substr($pharmacy->name, 0, 1)) }}</div>

                <h5 class="fw-bold mt-3 mb-1" style="color:var(--navy)" id="liveName">{{ $pharmacy->name }}</h5>

                <div class="mb-3">
                    <span class="pill {{ $status[0] }}"><i class="bi {{ $status[1] }}"></i>{{ $status[2] }}</span>
                </div>

                <div class="text-start">
                    <div class="info-line"><i class="bi bi-geo-alt-fill"></i><div>{{ $pharmacy->address }}<br><span class="text-muted">{{ $pharmacy->city }}@if($pharmacy->region), {{ $pharmacy->region }}@endif</span></div></div>
                    <div class="info-line"><i class="bi bi-telephone-fill"></i><div>{{ $pharmacy->phone ?: 'Téléphone non renseigné' }}</div></div>
                    <div class="info-line"><i class="bi {{ ($pharmacy->type ?? 'jour') === 'nuit' ? 'bi-moon-stars-fill' : 'bi-sun-fill' }}"></i><div>{{ ($pharmacy->type ?? 'jour') === 'nuit' ? 'Pharmacie de garde (nuit)' : 'Pharmacie de jour' }}</div></div>
                    <div class="info-line"><i class="bi bi-capsule"></i><div>{{ $stockCount }} référence(s) en stock</div></div>
                    <div class="info-line"><i class="bi bi-calendar-check"></i><div>Inscrite depuis le {{ $pharmacy->created_at->format('d/m/Y') }}</div></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== Formulaire ===== --}}
    <div class="col-lg-8">
        <div class="pf-card p-4 fade-up" style="--d:2">

            <form method="POST" action="{{ route('pharmacien.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="section-title"><span class="ic"><i class="bi bi-hospital"></i></span>Informations de la pharmacie</div>

                <div class="row g-3 mb-2">
                    <div class="col-12">
                        <label class="form-label">Nom de la pharmacie</label>
                        <div class="input-ic">
                            <i class="bi bi-shop"></i>
                            <input type="text" name="name" id="nameInput" class="form-control"
                                   value="{{ old('name', $pharmacy->name) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Adresse</label>
                        <div class="input-ic">
                            <i class="bi bi-signpost-2"></i>
                            <input type="text" name="address" id="addressInput" class="form-control"
                                   value="{{ old('address', $pharmacy->address) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ville / Délégation</label>
                        <div class="input-ic">
                            <i class="bi bi-buildings"></i>
                            <input type="text" name="city" id="cityInput" class="form-control"
                                   value="{{ old('city', $pharmacy->city) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gouvernorat</label>
                        <div class="input-ic">
                            <i class="bi bi-map"></i>
                            <select name="region" class="form-select">
                                <option value="">— Choisir —</option>
                                @foreach($gouvernorats as $g)
                                    <option value="{{ $g }}" @selected($currentRegion === $g)>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Téléphone de la pharmacie</label>
                        <div class="input-ic">
                            <i class="bi bi-telephone"></i>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $pharmacy->phone) }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--line)">

                <div class="section-title"><span class="ic"><i class="bi bi-clock-history"></i></span>Type de pharmacie</div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6 type-card">
                        <input type="radio" name="type" id="t-jour" value="jour" @checked($currentType === 'jour')>
                        <label for="t-jour">
                            <span class="ic"><i class="bi bi-sun-fill"></i></span>
                            <span>
                                <strong class="d-block">Pharmacie de jour</strong>
                                <span class="small text-muted">Horaires habituels</span>
                            </span>
                        </label>
                    </div>

                    <div class="col-md-6 type-card">
                        <input type="radio" name="type" id="t-nuit" value="nuit" @checked($currentType === 'nuit')>
                        <label for="t-nuit">
                            <span class="ic"><i class="bi bi-moon-stars-fill"></i></span>
                            <span>
                                <strong class="d-block">Pharmacie de garde</strong>
                                <span class="small text-muted">Ouverte la nuit</span>
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--line)">

                <div class="section-title"><span class="ic"><i class="bi bi-geo-alt"></i></span>Position sur la carte</div>

                <div class="gps-box">
                    <p class="small text-muted mb-3">
                        Une position précise permet aux patients de vous trouver directement dans Google Maps.
                        Cliquez sur le bouton quand vous êtes à la pharmacie.
                    </p>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" inputmode="decimal" name="latitude" id="lat" class="form-control"
                                   value="{{ old('latitude', $pharmacy->latitude) }}" placeholder="36.8065000">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" inputmode="decimal" name="longitude" id="lng" class="form-control"
                                   value="{{ old('longitude', $pharmacy->longitude) }}" placeholder="10.1815000">
                        </div>

                        <div class="col-md-4">
                            <button type="button" id="geoBtn" class="btn-soft w-100" style="height:48px">
                                <i class="bi bi-crosshair me-2"></i>Ma position
                            </button>
                        </div>
                    </div>

                    <a href="#" id="mapLink" target="_blank" rel="noopener" class="d-inline-block mt-3 small fw-semibold text-decoration-none" style="color:var(--teal)">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Vérifier dans Google Maps
                    </a>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn-teal">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    (function () {
        const lat = document.getElementById('lat');
        const lng = document.getElementById('lng');
        const link = document.getElementById('mapLink');
        const geoBtn = document.getElementById('geoBtn');
        const nameInput = document.getElementById('nameInput');
        const addressInput = document.getElementById('addressInput');
        const cityInput = document.getElementById('cityInput');

        function updateLink() {
            const la = lat.value.trim();
            const lo = lng.value.trim();

            if (la && lo) {
                link.href = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(la + ',' + lo);
            } else {
                const q = [nameInput.value, addressInput.value, cityInput.value, 'Tunisie'].join(' ');
                link.href = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(q);
            }
        }

        [lat, lng, nameInput, addressInput, cityInput].forEach(function (el) {
            el.addEventListener('input', updateLink);
        });

        updateLink();

        // Nom en direct dans la carte d'identité
        nameInput.addEventListener('input', function () {
            document.getElementById('liveName').textContent = nameInput.value || 'Ma pharmacie';
        });

        // Géolocalisation
        geoBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                toast("La géolocalisation n'est pas disponible sur ce navigateur.", 'danger');
                return;
            }

            geoBtn.disabled = true;
            geoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Recherche...';

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    lat.value = pos.coords.latitude.toFixed(7);
                    lng.value = pos.coords.longitude.toFixed(7);
                    updateLink();
                    toast('Position récupérée. N\'oubliez pas d\'enregistrer.', 'success');
                    geoBtn.disabled = false;
                    geoBtn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Ma position';
                },
                function () {
                    toast("Impossible d'obtenir votre position. Autorisez la localisation dans le navigateur.", 'danger');
                    geoBtn.disabled = false;
                    geoBtn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Ma position';
                },
                { enableHighAccuracy: true, timeout: 15000 }
            );
        });
    })();
</script>
@endpush
