@extends('layouts.pharmacien')

@section('title', 'Modifier le stock - PharmaFind')
@section('topbar', 'Modifier le stock')

@push('styles')
<style>
    .med-hero {
        border-radius: 18px;
        padding: 26px 28px;
        color: #fff;
        background: linear-gradient(125deg, #0D303D 0%, #123B4A 55%, #16A6A0 140%);
        box-shadow: 0 14px 32px rgba(18,59,74,.22);
        position: relative;
        overflow: hidden;
    }

    .med-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -60px;
        top: -90px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .med-hero > * { position: relative; z-index: 1; }

    .tag {
        display: inline-block;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        margin: 0 6px 6px 0;
    }

    .fact {
        background: #F8FBFC;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 12px 14px;
        height: 100%;
    }

    .fact .k { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); font-weight: 700; }
    .fact .v { font-weight: 600; color: var(--navy); margin-top: 3px; }

    .stepper-lg {
        display: flex;
        align-items: center;
        background: #F3F7F9;
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
    }

    .stepper-lg button {
        width: 64px;
        height: 64px;
        border: none;
        background: transparent;
        font-size: 22px;
        color: var(--navy);
        transition: .15s;
    }

    .stepper-lg button:hover { background: var(--teal); color: #fff; }

    .stepper-lg input {
        flex: 1;
        min-width: 0;
        height: 64px;
        border: none;
        text-align: center;
        font-size: 30px;
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
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy);
        transition: .15s;
    }

    .preset:hover { border-color: var(--teal); background: rgba(22,166,160,.08); }
    .preset.danger:hover { border-color: var(--danger); background: rgba(229,72,77,.08); color: var(--danger); }
</style>
@endpush

@section('content')

@php
    $initial = (int) $stock->declared_quantity;
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 fade-up" style="--d:0">
    <div>
        <h1 class="page-title">Modifier le stock</h1>
        <p class="page-subtitle mb-0">Mettez à jour la quantité disponible dans votre pharmacie.</p>
    </div>

    <a href="{{ route('pharmacien.medicaments.index') }}" class="btn-soft">
        <i class="bi bi-arrow-left me-2"></i>Retour au stock
    </a>
</div>

<div class="row g-4">

    <div class="col-lg-7">

        <div class="med-hero fade-up" style="--d:1">
            <div class="small text-uppercase fw-bold" style="color:#bfe6e8;letter-spacing:.06em">Médicament</div>
            <h3 class="fw-bold mb-3">{{ $medicament->name }}</h3>

            @if($medicament->dosage)<span class="tag"><i class="bi bi-droplet-half me-1"></i>{{ $medicament->dosage }}</span>@endif
            @if($medicament->form)<span class="tag"><i class="bi bi-capsule me-1"></i>{{ $medicament->form }}</span>@endif
            @if($medicament->category)<span class="tag"><i class="bi bi-tag me-1"></i>{{ $medicament->category }}</span>@endif
        </div>

        <div class="pf-card p-4 mt-4 fade-up" style="--d:2">
            <h6 class="card-title-pf mb-3"><i class="bi bi-info-circle-fill me-2 text-info"></i>Informations</h6>

            <div class="row g-3">
                <div class="col-md-6"><div class="fact"><div class="k">Principe actif</div><div class="v">{{ $medicament->active_ingredient ?? '—' }}</div></div></div>
                <div class="col-md-6"><div class="fact"><div class="k">Laboratoire</div><div class="v">{{ $medicament->laboratory ?? '—' }}</div></div></div>
                <div class="col-md-6"><div class="fact"><div class="k">Numéro AMM</div><div class="v">{{ $medicament->amm ?? '—' }}</div></div></div>
                <div class="col-md-6">
                    <div class="fact">
                        <div class="k">Dernière mise à jour</div>
                        <div class="v">
                            {{ $stock->updated_stock_at ? $stock->updated_stock_at->locale('fr')->diffForHumans() : 'Jamais' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-5">
        <div class="pf-card p-4 fade-up" style="--d:3">

            <form action="{{ route('pharmacien.medicaments.update', $medicament) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title-pf mb-0">Nouvelle quantité</h6>
                    <span class="pill" id="qtyPill"></span>
                </div>

                <div class="stepper-lg">
                    <button type="button" id="minus" aria-label="Diminuer"><i class="bi bi-dash-lg"></i></button>
                    <input type="number" name="declared_quantity" id="declared_quantity"
                           min="0" value="{{ old('declared_quantity', $initial) }}" required>
                    <button type="button" id="plus" aria-label="Augmenter"><i class="bi bi-plus-lg"></i></button>
                </div>

                @error('declared_quantity')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror

                <div class="small text-muted mt-3 mb-2">Ajouts rapides</div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="preset" data-add="5">+5</button>
                    <button type="button" class="preset" data-add="10">+10</button>
                    <button type="button" class="preset" data-add="25">+25</button>
                    <button type="button" class="preset" data-add="50">+50</button>
                    <button type="button" class="preset danger" data-set="0"><i class="bi bi-x-circle me-1"></i>Mettre en rupture</button>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-teal flex-grow-1">
                        <i class="bi bi-check2-circle me-2"></i>Enregistrer
                    </button>

                    <a href="{{ route('pharmacien.medicaments.index') }}" class="btn-soft">Annuler</a>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    (function () {
        const qty = document.getElementById('declared_quantity');
        const pill = document.getElementById('qtyPill');

        function paint() {
            const q = Math.max(0, parseInt(qty.value, 10) || 0);
            if (q <= 0) {
                pill.className = 'pill pill-out';
                pill.innerHTML = '<i class="bi bi-x-circle-fill"></i>Rupture';
            } else if (q <= 5) {
                pill.className = 'pill pill-low';
                pill.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>Stock faible';
            } else {
                pill.className = 'pill pill-ok';
                pill.innerHTML = '<i class="bi bi-check-circle-fill"></i>Disponible';
            }
        }

        document.getElementById('minus').addEventListener('click', function () {
            qty.value = Math.max(0, (parseInt(qty.value, 10) || 0) - 1);
            paint();
        });

        document.getElementById('plus').addEventListener('click', function () {
            qty.value = (parseInt(qty.value, 10) || 0) + 1;
            paint();
        });

        document.querySelectorAll('.preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (btn.dataset.add) {
                    qty.value = (parseInt(qty.value, 10) || 0) + parseInt(btn.dataset.add, 10);
                } else {
                    qty.value = btn.dataset.set;
                }
                paint();
            });
        });

        qty.addEventListener('input', paint);
        paint();
    })();
</script>
@endpush
