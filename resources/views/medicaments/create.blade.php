@extends('layouts.pharmacien')

@section('title', 'Ajouter un médicament - PharmaFind')
@section('topbar', 'Ajouter un médicament')

@push('styles')
<style>
    .step-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--teal);
        color: #fff;
        font-weight: 800;
        font-size: 14px;
        display: inline-grid;
        place-items: center;
        margin-right: 10px;
    }

    .big-search { position: relative; }

    .big-search i.bi-search {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--teal);
        font-size: 18px;
        pointer-events: none;
    }

    .big-search input { height: 58px; padding-left: 50px; font-size: 16px; border-width: 2px; }

    #suggestions {
        display: none;
        margin-top: 10px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(18,59,74,.12);
        max-height: 360px;
        overflow-y: auto;
    }

    .sg-item {
        padding: 13px 16px;
        border-bottom: 1px solid var(--line);
        cursor: pointer;
        transition: background .15s;
    }

    .sg-item:last-child { border-bottom: none; }
    .sg-item:hover, .sg-item.active { background: #F0FBFA; }
    .sg-item .t { font-weight: 700; color: var(--navy); }
    .sg-item .d { font-size: 12.5px; color: var(--muted); margin-top: 2px; }
    .sg-msg { padding: 16px; color: var(--muted); text-align: center; font-size: 14px; }

    .score {
        float: right;
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        background: rgba(22,166,160,.14);
        color: #0e7f7a;
    }

    .fact {
        background: #F8FBFC;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 12px 14px;
        height: 100%;
    }

    .fact .k { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); font-weight: 700; }
    .fact .v { font-weight: 600; color: var(--navy); margin-top: 3px; word-break: break-word; }

    #previewCard { display: none; border: 2px solid rgba(22,166,160,.35); }
    #previewCard.show { display: block; animation: fadeUp .5s ease; }

    .stepper-lg {
        display: flex;
        align-items: center;
        background: #F3F7F9;
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
    }

    .stepper-lg button {
        width: 56px;
        height: 56px;
        border: none;
        background: transparent;
        font-size: 20px;
        color: var(--navy);
        transition: .15s;
    }

    .stepper-lg button:hover { background: var(--teal); color: #fff; }

    .stepper-lg input {
        flex: 1;
        min-width: 0;
        height: 56px;
        border: none;
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        color: var(--navy);
        background: #fff;
        -moz-appearance: textfield;
    }

    .stepper-lg input::-webkit-outer-spin-button,
    .stepper-lg input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .stepper-lg input:focus { outline: none; background: #F0FBFA; }

    .preset {
        border: 1px dashed #BFD0D6;
        background: #fff;
        border-radius: 999px;
        padding: 5px 14px;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy);
        transition: .15s;
    }

    .preset:hover { border-color: var(--teal); background: rgba(22,166,160,.08); }
</style>
@endpush

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 fade-up" style="--d:0">
    <div>
        <h1 class="page-title">Ajouter un médicament</h1>
        <p class="page-subtitle mb-0">
            Recherchez-le dans le catalogue officiel des médicaments commercialisés en Tunisie.
        </p>
    </div>

    <a href="{{ route('pharmacien.medicaments.index') }}" class="btn-soft">
        <i class="bi bi-arrow-left me-2"></i>Retour au stock
    </a>
</div>

@if(session('duplicate'))
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Attention !</strong> {{ session('duplicate') }}
    </div>
@endif

<form action="{{ route('pharmacien.medicaments.store') }}" method="POST" id="medicamentForm">
    @csrf

    <input type="hidden" name="reference_id" id="reference_id" value="{{ old('reference_id') }}">

    <div class="row g-4">

        {{-- ===== Colonne gauche : recherche + fiche ===== --}}
        <div class="col-lg-8">

            <div class="pf-card p-4 fade-up" style="--d:1">
                <h6 class="card-title-pf mb-3">
                    <span class="step-badge">1</span>Recherche du médicament
                </h6>

                <div class="big-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="medicineSearch" class="form-control"
                           placeholder="Ex : DOLIPRANE, PARACETAMOL, AMOXICILLINE..."
                           autocomplete="off">
                </div>

                <div id="suggestions"></div>

                <div class="small text-muted mt-3">
                    <i class="bi bi-lightbulb-fill me-1" style="color:var(--warn)"></i>
                    Saisissez le nom commercial, le principe actif, la catégorie ou le laboratoire.
                    Même avec une faute de frappe, PharmaFind vous propose les médicaments correspondants.
                </div>
            </div>

            <div class="pf-card p-4 mt-4" id="previewCard">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title-pf mb-0">
                        <i class="bi bi-capsule-pill me-2" style="color:var(--teal)"></i>Fiche du médicament
                    </h6>
                    <span class="pill pill-ok"><i class="bi bi-check-circle-fill"></i>Sélectionné</span>
                </div>

                <h4 class="fw-bold mb-3" id="f-name" style="color:var(--navy)"></h4>

                <div class="row g-3">
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Principe actif</div><div class="v" id="f-active"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Dosage</div><div class="v" id="f-dosage"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Forme</div><div class="v" id="f-form"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Présentation</div><div class="v" id="f-presentation"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Catégorie</div><div class="v" id="f-category"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Laboratoire</div><div class="v" id="f-laboratory"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Numéro AMM</div><div class="v" id="f-amm"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">Type de produit</div><div class="v" id="f-product_type"></div></div></div>
                    <div class="col-md-6 col-xl-4"><div class="fact"><div class="k">VEIC</div><div class="v" id="f-veic"></div></div></div>
                    <div class="col-12"><div class="fact"><div class="k">Indications</div><div class="v fw-normal" id="f-indications"></div></div></div>
                </div>
            </div>

        </div>

        {{-- ===== Colonne droite : quantité ===== --}}
        <div class="col-lg-4">
            <div class="pf-card p-4 fade-up" style="--d:2; position:sticky; top:96px">

                <h6 class="card-title-pf mb-3">
                    <span class="step-badge">2</span>Stock de la pharmacie
                </h6>

                <label for="declared_quantity" class="form-label">Quantité disponible *</label>

                <div class="stepper-lg">
                    <button type="button" id="minus" aria-label="Diminuer"><i class="bi bi-dash-lg"></i></button>
                    <input type="number" name="declared_quantity" id="declared_quantity"
                           min="0" value="{{ old('declared_quantity', 0) }}" required>
                    <button type="button" id="plus" aria-label="Augmenter"><i class="bi bi-plus-lg"></i></button>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="button" class="preset" data-set="5">5</button>
                    <button type="button" class="preset" data-set="10">10</button>
                    <button type="button" class="preset" data-set="25">25</button>
                    <button type="button" class="preset" data-set="50">50</button>
                    <button type="button" class="preset" data-set="100">100</button>
                </div>

                <div class="mt-3">
                    <span class="pill" id="qtyPill"></span>
                </div>

                <button type="submit" id="submitButton" class="btn-teal w-100 mt-4" disabled>
                    <i class="bi bi-check2-circle me-2"></i>Enregistrer le médicament
                </button>

                <div class="small text-muted text-center mt-3" id="hint">
                    Sélectionnez d'abord un médicament dans la liste.
                </div>

            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    (function () {
        const searchUrl = @json(route('pharmacien.medicaments.search'));

        const searchInput = document.getElementById('medicineSearch');
        const suggestions = document.getElementById('suggestions');
        const referenceInput = document.getElementById('reference_id');
        const submitButton = document.getElementById('submitButton');
        const previewCard = document.getElementById('previewCard');
        const hint = document.getElementById('hint');
        const qty = document.getElementById('declared_quantity');
        const qtyPill = document.getElementById('qtyPill');

        let timer = null;
        let controller = null;
        let activeIndex = -1;

        const FIELDS = {
            'f-name': 'name',
            'f-active': 'active_ingredient',
            'f-dosage': 'dosage',
            'f-form': 'form',
            'f-presentation': 'presentation',
            'f-category': 'category',
            'f-laboratory': 'laboratory',
            'f-amm': 'amm',
            'f-product_type': 'product_type',
            'f-veic': 'veic',
            'f-indications': 'indications'
        };

        function setFacts(medicine) {
            Object.keys(FIELDS).forEach(function (id) {
                const value = medicine ? medicine[FIELDS[id]] : '';
                document.getElementById(id).textContent = value || '—';
            });
        }

        function resetSelection() {
            referenceInput.value = '';
            submitButton.disabled = true;
            previewCard.classList.remove('show');
            hint.textContent = "Sélectionnez d'abord un médicament dans la liste.";
        }

        function selectMedicine(medicine) {
            referenceInput.value = medicine.id;
            setFacts(medicine);

            searchInput.value = medicine.name;
            suggestions.style.display = 'none';

            previewCard.classList.remove('show');
            void previewCard.offsetWidth; // relance l'animation
            previewCard.classList.add('show');

            submitButton.disabled = false;
            hint.textContent = 'Indiquez la quantité puis enregistrez.';
            previewCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function createSuggestion(medicine) {
            const item = document.createElement('div');
            item.className = 'sg-item';

            const score = document.createElement('span');
            score.className = 'score';
            score.textContent = medicine.score + '%';

            const title = document.createElement('div');
            title.className = 't';
            title.textContent = medicine.name;

            const d1 = document.createElement('div');
            d1.className = 'd';
            d1.textContent = 'Principe actif : ' + (medicine.active_ingredient || '—') +
                ' · Dosage : ' + (medicine.dosage || '—') +
                ' · Forme : ' + (medicine.form || '—');

            const d2 = document.createElement('div');
            d2.className = 'd';
            d2.textContent = 'Laboratoire : ' + (medicine.laboratory || '—') +
                ' · Catégorie : ' + (medicine.category || '—');

            item.appendChild(score);
            item.appendChild(title);
            item.appendChild(d1);
            item.appendChild(d2);

            item.addEventListener('click', function () { selectMedicine(medicine); });
            item._medicine = medicine;

            return item;
        }

        function showMessage(text) {
            suggestions.innerHTML = '';
            const m = document.createElement('div');
            m.className = 'sg-msg';
            m.textContent = text;
            suggestions.appendChild(m);
            suggestions.style.display = 'block';
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            resetSelection();
            setFacts(null);
            activeIndex = -1;

            const term = this.value.trim();

            if (term.length < 2) {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
                return;
            }

            timer = setTimeout(async function () {

                if (controller) controller.abort();
                controller = new AbortController();

                showMessage('Recherche en cours...');

                try {
                    const response = await fetch(searchUrl + '?q=' + encodeURIComponent(term), {
                        headers: { 'Accept': 'application/json' },
                        signal: controller.signal
                    });

                    if (!response.ok) throw new Error('Erreur de recherche');

                    const results = await response.json();

                    if (results.length === 0) {
                        showMessage('Aucun médicament correspondant trouvé dans le catalogue.');
                        return;
                    }

                    suggestions.innerHTML = '';
                    results.forEach(function (medicine) {
                        suggestions.appendChild(createSuggestion(medicine));
                    });
                    suggestions.style.display = 'block';

                } catch (error) {
                    if (error.name !== 'AbortError') {
                        showMessage('Impossible de contacter le catalogue.');
                    }
                }

            }, 300);
        });

        // Navigation au clavier : flèches + Entrée
        searchInput.addEventListener('keydown', function (e) {
            const items = suggestions.querySelectorAll('.sg-item');
            if (!items.length || suggestions.style.display === 'none') return;

            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = e.key === 'ArrowDown'
                    ? Math.min(activeIndex + 1, items.length - 1)
                    : Math.max(activeIndex - 1, 0);
                items.forEach(function (it, i) { it.classList.toggle('active', i === activeIndex); });
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            }

            if (e.key === 'Enter' && activeIndex >= 0) {
                e.preventDefault();
                selectMedicine(items[activeIndex]._medicine);
            }
        });

        document.addEventListener('click', function (event) {
            if (!suggestions.contains(event.target) && event.target !== searchInput) {
                suggestions.style.display = 'none';
            }
        });

        // ---- Quantité ----
        function paintQty() {
            const q = Math.max(0, parseInt(qty.value, 10) || 0);
            if (q <= 0) {
                qtyPill.className = 'pill pill-out';
                qtyPill.innerHTML = '<i class="bi bi-x-circle-fill"></i>Rupture';
            } else if (q <= 5) {
                qtyPill.className = 'pill pill-low';
                qtyPill.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>Stock faible';
            } else {
                qtyPill.className = 'pill pill-ok';
                qtyPill.innerHTML = '<i class="bi bi-check-circle-fill"></i>Disponible';
            }
        }

        document.getElementById('minus').addEventListener('click', function () {
            qty.value = Math.max(0, (parseInt(qty.value, 10) || 0) - 1);
            paintQty();
        });

        document.getElementById('plus').addEventListener('click', function () {
            qty.value = (parseInt(qty.value, 10) || 0) + 1;
            paintQty();
        });

        document.querySelectorAll('.preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                qty.value = btn.dataset.set;
                paintQty();
            });
        });

        qty.addEventListener('input', paintQty);
        paintQty();

        document.getElementById('medicamentForm').addEventListener('submit', function (event) {
            if (!referenceInput.value) {
                event.preventDefault();
                toast('Veuillez sélectionner un médicament dans le catalogue.', 'warn');
            }
        });
    })();
</script>
@endpush
