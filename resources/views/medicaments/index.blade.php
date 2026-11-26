@extends('layouts.pharmacien')

@section('title', 'Mon stock - PharmaFind')
@section('topbar', 'Mon stock')

@push('styles')
<style>
    .search-wrap { position: relative; }

    .search-wrap i.bi-search {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .search-wrap input { padding-left: 44px; height: 50px; }

    .chip {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--text);
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .chip .c {
        background: #EEF3F5;
        border-radius: 999px;
        padding: 1px 9px;
        font-size: 12px;
    }

    .chip:hover { border-color: var(--teal); }

    .chip.active {
        background: var(--navy);
        border-color: var(--navy);
        color: #fff;
    }

    .chip.active .c { background: rgba(255,255,255,.2); color: #fff; }

    .stepper {
        display: inline-flex;
        align-items: center;
        background: #F3F7F9;
        border: 1px solid var(--line);
        border-radius: 12px;
        overflow: hidden;
    }

    .step-btn {
        width: 34px;
        height: 38px;
        border: none;
        background: transparent;
        color: var(--navy);
        font-size: 16px;
        transition: .15s;
    }

    .step-btn:hover { background: var(--teal); color: #fff; }

    .step-input {
        width: 58px;
        height: 38px;
        border: none;
        background: #fff;
        text-align: center;
        font-weight: 700;
        color: var(--navy);
        -moz-appearance: textfield;
    }

    .step-input::-webkit-outer-spin-button,
    .step-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .step-input:focus { outline: none; background: #F0FBFA; }

    .med-row.saving .stepper { opacity: .6; }

    .saved-flash {
        color: var(--teal);
        opacity: 0;
        transition: opacity .3s;
        margin-left: 8px;
    }

    .saved-flash.on { opacity: 1; }

    .icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: inline-grid;
        place-items: center;
        background: #F3F7F9;
        color: var(--navy);
        text-decoration: none;
        transition: .2s;
    }

    .icon-btn:hover { background: rgba(22,166,160,.18); color: var(--teal-dark); }
    .icon-btn.danger:hover { background: rgba(229,72,77,.15); color: var(--danger); }

    .med-name { font-weight: 700; color: var(--navy); }
</style>
@endpush

@section('content')

@php
    $LOW = 5;
    $qtyOf = fn ($m) => $m->stocks->first()->declared_quantity ?? 0;
    $cAll = $medicaments->count();
    $cOut = $medicaments->filter(fn ($m) => $qtyOf($m) <= 0)->count();
    $cLow = $medicaments->filter(fn ($m) => $qtyOf($m) > 0 && $qtyOf($m) <= $LOW)->count();
    $cOk  = $cAll - $cOut - $cLow;
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 fade-up" style="--d:0">
    <div>
        <h1 class="page-title">Mon stock</h1>
        <p class="page-subtitle mb-0">
            Ajustez vos quantités en un clic : les patients voient le stock en temps réel.
        </p>
    </div>

    <a href="{{ route('pharmacien.medicaments.create') }}" class="btn-teal">
        <i class="bi bi-plus-circle me-2"></i>Ajouter un médicament
    </a>
</div>

<div class="pf-card p-4 fade-up" style="--d:1">

    {{-- Recherche --}}
    <form action="{{ route('pharmacien.medicaments.index') }}" method="GET" class="d-flex flex-wrap gap-2 mb-3">
        <div class="search-wrap flex-grow-1">
            <i class="bi bi-search"></i>
            <input type="search" name="q" id="liveSearch" class="form-control"
                   value="{{ $search }}" autocomplete="off"
                   placeholder="Rechercher par nom, principe actif, catégorie ou laboratoire...">
        </div>

        @if($search)
            <a href="{{ route('pharmacien.medicaments.index') }}" class="btn-soft">
                <i class="bi bi-x-circle me-2"></i>Réinitialiser
            </a>
        @endif
    </form>

    {{-- Filtres --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <button type="button" class="chip active" data-filter="all">Tous <span class="c" data-count-of="all">{{ $cAll }}</span></button>
        <button type="button" class="chip" data-filter="ok"><i class="bi bi-check-circle-fill" style="color:var(--teal)"></i>Disponibles <span class="c" data-count-of="ok">{{ $cOk }}</span></button>
        <button type="button" class="chip" data-filter="low"><i class="bi bi-exclamation-triangle-fill" style="color:var(--warn)"></i>Stock faible <span class="c" data-count-of="low">{{ $cLow }}</span></button>
        <button type="button" class="chip" data-filter="out"><i class="bi bi-x-circle-fill" style="color:var(--danger)"></i>Ruptures <span class="c" data-count-of="out">{{ $cOut }}</span></button>
    </div>

    @if($cAll === 0)

        <div class="text-center py-5">
            <div class="stat-icon ic-teal mx-auto mb-3" style="width:76px;height:76px;font-size:34px;border-radius:22px">
                <i class="bi bi-capsule-pill"></i>
            </div>
            <h5 class="fw-bold" style="color:var(--navy)">
                {{ $search ? 'Aucun résultat pour votre recherche' : 'Aucun médicament dans votre stock' }}
            </h5>
            <p class="text-muted">
                {{ $search ? 'Essayez un autre nom ou réinitialisez la recherche.' : 'Commencez par ajouter votre premier médicament.' }}
            </p>
            @unless($search)
                <a href="{{ route('pharmacien.medicaments.create') }}" class="btn-teal">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un médicament
                </a>
            @endunless
        </div>

    @else

        <div class="table-responsive">
            <table class="table pf-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Médicament</th>
                        <th class="d-none d-lg-table-cell">Dosage / Forme</th>
                        <th class="d-none d-xl-table-cell">Laboratoire</th>
                        <th>Statut</th>
                        <th>Quantité</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody id="rows">
                    @foreach($medicaments as $medicament)
                        @php $qty = $qtyOf($medicament); @endphp

                        <tr class="med-row"
                            data-url="{{ route('pharmacien.medicaments.update', $medicament) }}"
                            data-qty="{{ $qty }}"
                            data-saved="{{ $qty }}"
                            data-search="{{ mb_strtolower(\Illuminate\Support\Str::ascii($medicament->name.' '.$medicament->active_ingredient.' '.$medicament->laboratory.' '.$medicament->category)) }}">

                            <td>
                                <div class="med-name">{{ $medicament->name }}</div>
                                <div class="small text-muted">{{ $medicament->active_ingredient ?? '—' }}</div>
                            </td>

                            <td class="d-none d-lg-table-cell">
                                <div>{{ $medicament->dosage ?? '—' }}</div>
                                <div class="small text-muted">{{ $medicament->form ?? '—' }}</div>
                            </td>

                            <td class="d-none d-xl-table-cell small">{{ $medicament->laboratory ?? '—' }}</td>

                            <td><span class="pill status-pill"></span></td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="stepper">
                                        <button type="button" class="step-btn" data-delta="-1" aria-label="Diminuer"><i class="bi bi-dash-lg"></i></button>
                                        <input type="number" min="0" class="step-input" value="{{ $qty }}" aria-label="Quantité">
                                        <button type="button" class="step-btn" data-delta="1" aria-label="Augmenter"><i class="bi bi-plus-lg"></i></button>
                                    </div>
                                    <i class="bi bi-check2-circle saved-flash"></i>
                                </div>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('pharmacien.medicaments.edit', $medicament) }}" class="icon-btn" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button" class="icon-btn danger" title="Retirer du stock"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-url="{{ route('pharmacien.medicaments.destroy', $medicament) }}"
                                        data-name="{{ $medicament->name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <tr id="noResult" style="display:none">
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-search fs-2 d-block mb-2"></i>
                            Aucun médicament ne correspond à ce filtre.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif

</div>

{{-- Fenêtre de confirmation de suppression --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px">
            <form method="POST" id="deleteForm" action="#">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center p-4">
                    <div class="stat-icon ic-danger mx-auto mb-3" style="width:68px;height:68px;font-size:30px;border-radius:20px">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <h5 class="fw-bold" style="color:var(--navy)">Retirer ce médicament ?</h5>
                    <p class="text-muted mb-4">
                        <strong id="deleteName"></strong> sera retiré de votre stock
                        et n'apparaîtra plus dans les recherches des patients.
                    </p>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn-soft" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger fw-semibold" style="border-radius:11px;padding:11px 20px">
                            Oui, retirer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        const LOW = 5;
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const rows = Array.prototype.slice.call(document.querySelectorAll('.med-row'));
        const chips = document.querySelectorAll('.chip');
        const search = document.getElementById('liveSearch');
        const noResult = document.getElementById('noResult');

        let currentFilter = 'all';

        const normalize = function (s) {
            return (s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
        };

        const statusOf = function (q) { return q <= 0 ? 'out' : (q <= LOW ? 'low' : 'ok'); };

        const LABELS = {
            ok:  { cls: 'pill-ok',  html: '<i class="bi bi-check-circle-fill"></i>Disponible' },
            low: { cls: 'pill-low', html: '<i class="bi bi-exclamation-triangle-fill"></i>Stock faible' },
            out: { cls: 'pill-out', html: '<i class="bi bi-x-circle-fill"></i>Rupture' }
        };

        function paintRow(row) {
            const q = parseInt(row.dataset.qty, 10) || 0;
            const s = statusOf(q);
            row.dataset.status = s;
            const pill = row.querySelector('.status-pill');
            pill.className = 'pill status-pill ' + LABELS[s].cls;
            pill.innerHTML = LABELS[s].html;
        }

        function updateCounts() {
            const c = { all: rows.length, ok: 0, low: 0, out: 0 };
            rows.forEach(function (r) { c[r.dataset.status]++; });
            Object.keys(c).forEach(function (k) {
                const el = document.querySelector('[data-count-of="' + k + '"]');
                if (el) el.textContent = c[k];
            });
        }

        function applyFilters() {
            const term = normalize(search ? search.value : '');
            let visible = 0;

            rows.forEach(function (row) {
                const okText = term === '' || row.dataset.search.indexOf(term) !== -1;
                const okStatus = currentFilter === 'all' || row.dataset.status === currentFilter;
                const show = okText && okStatus;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (noResult) noResult.style.display = (rows.length > 0 && visible === 0) ? '' : 'none';
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(function (c) { c.classList.remove('active'); });
                chip.classList.add('active');
                currentFilter = chip.dataset.filter;
                applyFilters();
            });
        });

        if (search) search.addEventListener('input', applyFilters);

        // ---- Modification de quantité avec enregistrement automatique ----
        const timers = new Map();

        function scheduleSave(row) {
            clearTimeout(timers.get(row));
            timers.set(row, setTimeout(function () { save(row); }, 600));
        }

        async function save(row) {
            const input = row.querySelector('.step-input');
            const qty = Math.max(0, parseInt(input.value, 10) || 0);
            const previous = parseInt(row.dataset.saved, 10) || 0;
            input.value = qty;

            if (qty === previous) return;

            const body = new FormData();
            body.append('_method', 'PUT');
            body.append('declared_quantity', qty);

            row.classList.add('saving');

            try {
                const res = await fetch(row.dataset.url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: body
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);

                row.dataset.saved = qty;
                row.dataset.qty = qty;
                paintRow(row);
                updateCounts();
                applyFilters();

                const flash = row.querySelector('.saved-flash');
                flash.classList.add('on');
                setTimeout(function () { flash.classList.remove('on'); }, 1400);

                const name = row.querySelector('.med-name').textContent;
                toast('Stock de « ' + name + ' » mis à jour : ' + qty + ' unité(s).',
                      qty <= 0 ? 'warn' : 'success');

            } catch (e) {
                input.value = previous;
                row.dataset.qty = previous;
                paintRow(row);
                updateCounts();
                applyFilters();
                toast("Impossible d'enregistrer la quantité. Réessayez.", 'danger');
            } finally {
                row.classList.remove('saving');
            }
        }

        rows.forEach(function (row) {
            paintRow(row);

            const input = row.querySelector('.step-input');

            row.querySelectorAll('.step-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const next = Math.max(0, (parseInt(input.value, 10) || 0) + parseInt(btn.dataset.delta, 10));
                    input.value = next;
                    row.dataset.qty = next;
                    paintRow(row);
                    updateCounts();
                    scheduleSave(row);
                });
            });

            input.addEventListener('input', function () {
                row.dataset.qty = Math.max(0, parseInt(input.value, 10) || 0);
                paintRow(row);
                updateCounts();
                scheduleSave(row);
            });
        });

        updateCounts();
        applyFilters();

        // ---- Fenêtre de suppression ----
        const modal = document.getElementById('deleteModal');
        modal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            document.getElementById('deleteForm').action = btn.dataset.url;
            document.getElementById('deleteName').textContent = btn.dataset.name;
        });
    })();
</script>
@endpush
