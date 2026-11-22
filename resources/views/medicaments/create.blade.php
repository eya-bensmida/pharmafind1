
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajouter un médicament - PharmaFind</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7f6;
            margin: 0;
            padding: 30px 15px;
            color: #263238;
        }

        .container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
        }

        h1 { color: #167d63; }

        .subtitle { color: #64748b; }

        .section-title {
            color: #167d63;
            font-size: 18px;
            margin: 28px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .field { margin-bottom: 15px; }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        input[readonly], textarea[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        input:focus {
            outline: none;
            border-color: #167d63;
        }

        .full { grid-column: 1 / -1; }

        .suggestions {
            display: none;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            position: relative;
            z-index: 10;
        }

        .suggestion {
            padding: 12px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .suggestion:hover { background: #eff6ff; }

        .suggestion strong { color: #167d63; }

        .suggestion small {
            display: block;
            color: #64748b;
            margin-top: 5px;
        }

        .notice {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .info { background: #eff6ff; color: #1e40af; }
        .warning { background: #fff7ed; color: #9a3412; }
        .success { background: #dcfce7; color: #166534; }

        .selected {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin: 12px 0;
            display: none;
        }

        .error {
            color: #dc2626;
            margin-top: 6px;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 25px;
        }

        button {
            background: #167d63;
            color: white;
            border: none;
            padding: 13px 22px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        a {
            color: #167d63;
            text-decoration: none;
        }

        @media(max-width: 650px) {
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .container { padding: 20px; }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Ajouter un médicament</h1>

    <p class="subtitle">
        Recherchez un médicament dans le catalogue officiel
        des médicaments commercialisés en Tunisie.
    </p>

    @if(session('duplicate'))
        <div class="notice warning">
            <strong>Attention !</strong>
            {{ session('duplicate') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notice warning">
            <strong>Veuillez corriger les erreurs :</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="notice success">
            {{ session('success') }}
        </div>
    @endif

    <div class="notice info">
        <strong>Recherche intelligente :</strong>
        saisissez le nom commercial, le principe actif,
        la catégorie ou le laboratoire.
        Même avec une faute de frappe, PharmaFind proposera
        les médicaments correspondants du catalogue.
    </div>

    <form
        action="{{ route('pharmacien.medicaments.store') }}"
        method="POST"
        id="medicamentForm"
    >
        @csrf

        <input
            type="hidden"
            name="reference_id"
            id="reference_id"
            value="{{ old('reference_id') }}"
        >

        <h3 class="section-title">1. Recherche du médicament</h3>

        <div class="field">
            <label for="medicineSearch">
                Nom, principe actif, catégorie ou laboratoire *
            </label>

            <input
                type="search"
                id="medicineSearch"
                placeholder="Exemple : DOLIPRANE ou PARACETAMOL..."
                autocomplete="off"
            >

            <div id="suggestions" class="suggestions"></div>
        </div>

        <div id="selectedMessage" class="selected">
            Médicament sélectionné.
        </div>

        <h3 class="section-title">2. Informations du médicament</h3>

        <div class="grid">

            <div class="field full">
                <label>Nom commercial</label>
                <input type="text" id="name" readonly>
            </div>

            <div class="field">
                <label>Principe actif</label>
                <input type="text" id="active_ingredient" readonly>
            </div>

            <div class="field">
                <label>Dosage</label>
                <input type="text" id="dosage" readonly>
            </div>

            <div class="field">
                <label>Forme pharmaceutique</label>
                <input type="text" id="form" readonly>
            </div>

            <div class="field">
                <label>Présentation</label>
                <input type="text" id="presentation" readonly>
            </div>

            <div class="field">
                <label>Catégorie</label>
                <input type="text" id="category" readonly>
            </div>

            <div class="field">
                <label>Laboratoire</label>
                <input type="text" id="laboratory" readonly>
            </div>

            <div class="field">
                <label>Numéro AMM</label>
                <input type="text" id="amm" readonly>
            </div>

            <div class="field">
                <label>Type de produit</label>
                <input type="text" id="product_type" readonly>
            </div>

            <div class="field">
                <label>VEIC</label>
                <input type="text" id="veic" readonly>
            </div>

            <div class="field full">
                <label>Indications</label>
                <textarea id="indications" readonly></textarea>
            </div>

        </div>

        <h3 class="section-title">3. Stock de la pharmacie</h3>

        <div class="field">
            <label for="declared_quantity">
                Quantité disponible *
            </label>

            <input
                type="number"
                name="declared_quantity"
                id="declared_quantity"
                min="0"
                value="{{ old('declared_quantity', 0) }}"
                required
            >
        </div>

        <div class="actions">

            <button type="submit" id="submitButton" disabled>
                Enregistrer le médicament
            </button>

            <a href="{{ route('pharmacien.medicaments.index') }}">
                Annuler
            </a>

        </div>

    </form>

</div>

<script>
    const searchUrl = @json(route('pharmacien.medicaments.search'));

    const searchInput = document.getElementById('medicineSearch');
    const suggestions = document.getElementById('suggestions');
    const referenceInput = document.getElementById('reference_id');
    const submitButton = document.getElementById('submitButton');
    const selectedMessage = document.getElementById('selectedMessage');

    let timer = null;
    let controller = null;

    function fillField(id, value) {
        document.getElementById(id).value = value ?? '';
    }

    function selectMedicine(medicine) {

        referenceInput.value = medicine.id;

        fillField('name', medicine.name);
        fillField('active_ingredient', medicine.active_ingredient);
        fillField('dosage', medicine.dosage);
        fillField('form', medicine.form);
        fillField('presentation', medicine.presentation);
        fillField('category', medicine.category);
        fillField('laboratory', medicine.laboratory);
        fillField('amm', medicine.amm);
        fillField('product_type', medicine.product_type);
        fillField('veic', medicine.veic);
        fillField('indications', medicine.indications);

        searchInput.value = medicine.name;

        suggestions.style.display = 'none';

        selectedMessage.style.display = 'block';
        selectedMessage.textContent =
            'Médicament sélectionné : ' + medicine.name;

        submitButton.disabled = false;
    }

    function createSuggestion(medicine) {

        const item = document.createElement('div');
        item.className = 'suggestion';

        const title = document.createElement('strong');
        title.textContent = medicine.name;

        const details = document.createElement('small');

        details.textContent =
            'Principe actif : ' + (medicine.active_ingredient || '-') +
            ' | Dosage : ' + (medicine.dosage || '-') +
            ' | Forme : ' + (medicine.form || '-');

        const additional = document.createElement('small');

        additional.textContent =
            'Laboratoire : ' + (medicine.laboratory || '-') +
            ' | Catégorie : ' + (medicine.category || '-') +
            ' | Correspondance : ' + medicine.score + '%';

        item.appendChild(title);
        item.appendChild(details);
        item.appendChild(additional);

        item.addEventListener('click', function () {
            selectMedicine(medicine);
        });

        return item;
    }

    searchInput.addEventListener('input', function () {

        clearTimeout(timer);

        referenceInput.value = '';
        submitButton.disabled = true;
        selectedMessage.style.display = 'none';

        [
            'name',
            'active_ingredient',
            'dosage',
            'form',
            'presentation',
            'category',
            'laboratory',
            'amm',
            'product_type',
            'veic',
            'indications'
        ].forEach(id => fillField(id, ''));

        const term = this.value.trim();

        if (term.length < 2) {
            suggestions.innerHTML = '';
            suggestions.style.display = 'none';
            return;
        }

        timer = setTimeout(async function () {

            if (controller) {
                controller.abort();
            }

            controller = new AbortController();

            suggestions.innerHTML = '<div class="suggestion">Recherche en cours...</div>';
            suggestions.style.display = 'block';

            try {

                const response = await fetch(
                    searchUrl + '?q=' + encodeURIComponent(term),
                    {
                        headers: {
                            'Accept': 'application/json'
                        },
                        signal: controller.signal
                    }
                );

                if (!response.ok) {
                    throw new Error('Erreur de recherche');
                }

                const results = await response.json();

                suggestions.innerHTML = '';

                if (results.length === 0) {

                    suggestions.innerHTML =
                        '<div class="suggestion">Aucun médicament correspondant trouvé dans le catalogue.</div>';

                    return;
                }

                results.forEach(function (medicine) {
                    suggestions.appendChild(
                        createSuggestion(medicine)
                    );
                });

            } catch (error) {

                if (error.name !== 'AbortError') {
                    suggestions.innerHTML =
                        '<div class="suggestion">Impossible de contacter le catalogue.</div>';
                }
            }

        }, 300);
    });

    document.addEventListener('click', function (event) {

        if (
            !suggestions.contains(event.target) &&
            event.target !== searchInput
        ) {
            suggestions.style.display = 'none';
        }
    });

    document.getElementById('medicamentForm')
        .addEventListener('submit', function (event) {

            if (!referenceInput.value) {
                event.preventDefault();
                alert('Veuillez sélectionner un médicament dans le catalogue.');
            }
        });

</script>

</body>
</html>
