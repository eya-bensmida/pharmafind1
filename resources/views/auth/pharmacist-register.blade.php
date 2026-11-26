
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription pharmacie - PharmaFind</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #F5F8FA;
            font-family: Arial, sans-serif;
        }

        .registration-card {
            max-width: 850px;
            margin: 45px auto;
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(18,59,74,.08);
        }

        .section-title {
            color: #123B4A;
            font-weight: 700;
            border-bottom: 1px solid #e9eef1;
            padding-bottom: 12px;
            margin: 30px 0 20px;
        }

        .btn-main {
            background: #16A6A0;
            color: white;
        }

        .btn-main:hover {
            background: #128D88;
            color: white;
        }

        .suggestions-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            max-height: 260px;
            overflow-y: auto;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(18,59,74,.12);
        }

        .suggestion-item {
            display: block;
            width: 100%;
            padding: 12px 15px;
            text-align: left;
            border: 0;
            border-bottom: 1px solid #edf0f2;
            background: white;
        }

        .suggestion-item:hover {
            background: #eef8f8;
        }

        .suggestion-item:last-child {
            border-bottom: 0;
        }

        .coordinates-box {
            background: #F5F8FA;
            border-radius: 10px;
            padding: 15px;
        }

        .form-control:read-only {
            background-color: #F5F8FA;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="registration-card">

        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#123B4A">
                <i class="bi bi-capsule me-2"></i>
                PharmaFind
            </h2>

            <h4>Demande d'association à une pharmacie</h4>

            <p class="text-muted">
                Recherchez votre pharmacie existante et transmettez vos justificatifs.
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('pharmacien.register.store') }}"
              enctype="multipart/form-data">

            @csrf

            <h5 class="section-title">1. Informations personnelles</h5>

            <div class="mb-3">
                <label class="form-label">Nom et prénom *</label>
                <input type="text"
                       name="full_name"
                       class="form-control"
                       value="{{ old('full_name') }}"
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Adresse e-mail *</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Téléphone personnel *</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone') }}"
                           required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirmer le mot de passe *</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           required>
                </div>
            </div>

            <h5 class="section-title">2. Identification de la pharmacie</h5>

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Saisissez le nom de votre pharmacie. Sélectionnez-la dans
                les suggestions pour récupérer ses informations existantes.
            </div>

            <input type="hidden"
                   name="pharmacy_id"
                   id="pharmacy_id"
                   required>

            <div class="mb-3 position-relative">
                <label class="form-label">Nom officiel de la pharmacie *</label>

                <input type="text"
                       id="pharmacy_search"
                       name="name"
                       class="form-control"
                       placeholder="Commencez à saisir le nom..."
                       autocomplete="off"
                       value="{{ old('name') }}"
                       required>

                <div id="pharmacy_suggestions"
                     class="suggestions-list d-none"></div>

                <div id="search_message"
                     class="form-text">
                    Saisissez au moins 2 caractères pour afficher les suggestions.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse complète *</label>
                <input type="text"
                       name="address"
                       id="pharmacy_address"
                       class="form-control"
                       value="{{ old('address') }}"
                       readonly
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville *</label>
                    <input type="text"
                           name="city"
                           id="pharmacy_city"
                           class="form-control"
                           value="{{ old('city') }}"
                           readonly
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Gouvernorat</label>
                    <input type="text"
                           name="region"
                           id="pharmacy_region"
                           class="form-control"
                           value="{{ old('region') }}"
                           readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Téléphone de la pharmacie</label>
                <input type="text"
                       name="pharmacy_phone"
                       id="pharmacy_phone"
                       class="form-control"
                       placeholder="Téléphone de la pharmacie">
                <div class="form-text">
                    Si le numéro existe déjà, il sera prérempli.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Type de pharmacie *</label>

                <select name="type" id="pharmacy_type" class="form-select" required>
                    <option value="">Choisir le type</option>
                    <option value="jour" {{ old('type') === 'jour' ? 'selected' : '' }}>
                        Pharmacie de jour
                    </option>
                    <option value="nuit" {{ old('type') === 'nuit' ? 'selected' : '' }}>
                        Pharmacie de nuit
                    </option>
                </select>

                <div class="form-text">
                    Le type doit être confirmé par le responsable de la pharmacie.
                </div>
            </div>

            <div class="coordinates-box mb-3">
                <h6 class="fw-bold" style="color:#123B4A">
                    <i class="bi bi-geo-alt me-2"></i>
                    Localisation GPS
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Latitude</label>
                        <input type="text"
                               id="pharmacy_latitude"
                               class="form-control"
                               readonly
                               placeholder="Automatiquement renseignée">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label">Longitude</label>
                        <input type="text"
                               id="pharmacy_longitude"
                               class="form-control"
                               readonly
                               placeholder="Automatiquement renseignée">
                    </div>
                </div>

                <small class="text-muted">
                    Les coordonnées sont récupérées depuis la pharmacie sélectionnée.
                </small>
            </div>

            <div id="selected_message"
                 class="alert alert-success d-none">
                <i class="bi bi-check-circle me-2"></i>
                Pharmacie sélectionnée avec succès.
            </div>

            <h5 class="section-title">3. Documents justificatifs</h5>

            <div class="alert alert-info">
                Les documents doivent être des justificatifs authentiques
                correspondant à la pharmacie et au responsable.
                Formats acceptés : PDF, JPG, JPEG et PNG.
                Taille maximale : 5 Mo par fichier.
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Autorisation ou justificatif officiel de la pharmacie *
                </label>

                <input type="file"
                       name="authorization_document"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    Justificatif professionnel du responsable *
                </label>

                <input type="file"
                       name="order_document"
                       class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png"
                       required>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i>
                Votre compte restera désactivé jusqu'à l'approbation
                de votre demande par l'administrateur.
            </div>

            <button type="submit" class="btn btn-main w-100 py-3 fw-bold">
                Envoyer ma demande
                <i class="bi bi-arrow-right ms-2"></i>
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Retour à la connexion</a>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('pharmacy_search');
    const suggestions = document.getElementById('pharmacy_suggestions');
    const pharmacyId = document.getElementById('pharmacy_id');
    const searchMessage = document.getElementById('search_message');
    const selectedMessage = document.getElementById('selected_message');

    const addressInput = document.getElementById('pharmacy_address');
    const cityInput = document.getElementById('pharmacy_city');
    const regionInput = document.getElementById('pharmacy_region');
    const phoneInput = document.getElementById('pharmacy_phone');
    const latitudeInput = document.getElementById('pharmacy_latitude');
    const longitudeInput = document.getElementById('pharmacy_longitude');

    let searchTimeout = null;
    let currentRequest = null;

    function clearSelection() {
        pharmacyId.value = '';
        addressInput.value = '';
        cityInput.value = '';
        regionInput.value = '';
        phoneInput.value = '';
        phoneInput.readOnly = false;
        latitudeInput.value = '';
        longitudeInput.value = '';

        selectedMessage.classList.add('d-none');
    }

    function hideSuggestions() {
        suggestions.innerHTML = '';
        suggestions.classList.add('d-none');
    }

    function selectPharmacy(pharmacy) {
        pharmacyId.value = pharmacy.id;

        searchInput.value = pharmacy.name || '';
        addressInput.value = pharmacy.address || '';
        cityInput.value = pharmacy.city || '';
        regionInput.value = pharmacy.region || '';

        phoneInput.value = pharmacy.phone || '';
        phoneInput.readOnly = Boolean(pharmacy.phone);

        latitudeInput.value = pharmacy.latitude ?? '';
        longitudeInput.value = pharmacy.longitude ?? '';

        hideSuggestions();

        searchMessage.textContent = 'Pharmacie sélectionnée.';
        selectedMessage.classList.remove('d-none');
    }

    function displaySuggestions(pharmacies) {
        hideSuggestions();

        if (!pharmacies.length) {
            searchMessage.textContent =
                'Aucune pharmacie disponible ne correspond à cette recherche.';
            return;
        }

        pharmacies.forEach(function (pharmacy) {
            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'suggestion-item';

            const name = document.createElement('strong');
            name.textContent = pharmacy.name || 'Nom non renseigné';

            const details = document.createElement('div');
            details.className = 'small text-muted';
            details.textContent =
                [pharmacy.address, pharmacy.city, pharmacy.region]
                    .filter(Boolean)
                    .join(' - ');

            button.appendChild(name);
            button.appendChild(details);

            button.addEventListener('click', function () {
                selectPharmacy(pharmacy);
            });

            suggestions.appendChild(button);
        });

        suggestions.classList.remove('d-none');
        searchMessage.textContent = 'Sélectionnez votre pharmacie dans la liste.';
    }

    searchInput.addEventListener('input', function () {
        const term = searchInput.value.trim();

        clearSelection();
        hideSuggestions();

        if (currentRequest) {
            currentRequest.abort();
            currentRequest = null;
        }

        clearTimeout(searchTimeout);

        if (term.length < 2) {
            searchMessage.textContent =
                'Saisissez au moins 2 caractères pour afficher les suggestions.';
            return;
        }

        searchMessage.textContent = 'Recherche en cours...';

        searchTimeout = setTimeout(function () {

            currentRequest = new AbortController();

            fetch(
                "{{ route('pharmacien.register.search') }}?q=" +
                encodeURIComponent(term),
                {
                    headers: {
                        'Accept': 'application/json'
                    },
                    signal: currentRequest.signal
                }
            )
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Erreur lors de la recherche.');
                }

                return response.json();
            })
            .then(function (data) {
                if (searchInput.value.trim() !== term) {
                    return;
                }

                displaySuggestions(data);
            })
            .catch(function (error) {
                if (error.name !== 'AbortError') {
                    searchMessage.textContent =
                        'Impossible de charger les suggestions. Réessayez.';
                    console.error(error);
                }
            });

        }, 300);
    });

    document.addEventListener('click', function (event) {
        if (
            !searchInput.contains(event.target) &&
            !suggestions.contains(event.target)
        ) {
            hideSuggestions();
        }
    });

});
</script>

</body>
</html>
