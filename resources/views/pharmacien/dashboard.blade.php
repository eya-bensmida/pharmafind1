@extends('layouts.pharmacien')

@section('title', 'Tableau de bord - PharmaFind')
@section('topbar', 'Tableau de bord')

@push('styles')
<style>
    .hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 34px 38px;
        color: #fff;
        background: linear-gradient(125deg, #0D303D 0%, #123B4A 45%, #16A6A0 135%);
        box-shadow: 0 18px 40px rgba(18,59,74,.25);
    }

    .hero::before,
    .hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }

    .hero::before { width: 320px; height: 320px; right: -80px; top: -140px; }
    .hero::after { width: 200px; height: 200px; right: 160px; bottom: -120px; background: rgba(22,166,160,.25); }

    .hero > * { position: relative; z-index: 1; }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 14px;
    }

    .hero-title { font-size: 30px; font-weight: 800; margin: 0 0 6px; }
    .hero-sub { color: #cfe7e9; margin: 0 0 22px; font-size: 14.5px; }

    .hero-date {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 16px;
        padding: 16px 20px;
        text-align: center;
    }

    .hero-date .d { font-size: 12px; color: #cfe7e9; text-transform: capitalize; }
    .hero-date .t { font-size: 22px; font-weight: 800; letter-spacing: .02em; }

    .stat-value { font-size: 32px; font-weight: 800; color: var(--navy); line-height: 1.1; }
    .stat-label { color: var(--muted); font-size: 13px; font-weight: 500; }

    .insight {
        display: flex;
        align-items: center;
        gap: 14px;
        background: linear-gradient(90deg, rgba(245,165,36,.16), rgba(245,165,36,.05));
        border: 1px solid rgba(245,165,36,.35);
        border-radius: 14px;
        padding: 14px 18px;
        color: #7a4f00;
    }

    .insight i { font-size: 22px; color: #c47f00; }

    .chart-box { position: relative; height: 270px; }

    .donut-center {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        text-align: center;
        pointer-events: none;
    }

    .donut-center .v { font-size: 34px; font-weight: 800; color: var(--navy); line-height: 1; }
    .donut-center .l { font-size: 12px; color: var(--muted); }

    .legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; }

    .list-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 1px dashed var(--line);
    }

    .list-row:last-child { border-bottom: none; }

    .list-row .n { font-weight: 600; font-size: 14px; }
    .list-row .s { font-size: 12px; color: var(--muted); }

    .timeline-dot {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        background: rgba(22,166,160,.12);
        color: var(--teal);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .empty-state { text-align: center; padding: 56px 20px; }

    .empty-state .big-ic {
        width: 84px;
        height: 84px;
        margin: 0 auto 18px;
        border-radius: 24px;
        background: rgba(22,166,160,.12);
        color: var(--teal);
        display: grid;
        place-items: center;
        font-size: 38px;
    }
</style>
@endpush

@section('content')

@php
    $salut = now()->hour < 18 ? 'Bonjour' : 'Bonsoir';
    $type = $pharmacy->type ?? 'jour';
@endphp

{{-- ===== Bandeau d'accueil ===== --}}
<div class="hero fade-up" style="--d:0">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">

            @if($pharmacy->verification_status === 'approved')
                <div class="hero-kicker">
                    <i class="bi bi-patch-check-fill"></i> Pharmacie vérifiée
                </div>
            @endif

            <h1 class="hero-title">{{ $salut }}, {{ auth()->user()->name }} 👋</h1>

            <p class="hero-sub">
                <i class="bi bi-geo-alt-fill me-1"></i>
                {{ $pharmacy->name }} · {{ $pharmacy->city }}@if($pharmacy->region), {{ $pharmacy->region }}@endif
                &nbsp;·&nbsp;
                <i class="bi {{ $type === 'nuit' ? 'bi-moon-stars-fill' : 'bi-sun-fill' }} me-1"></i>
                {{ $type === 'nuit' ? 'Pharmacie de garde (nuit)' : 'Pharmacie de jour' }}
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('pharmacien.medicaments.create') }}" class="btn btn-light fw-bold"
                   style="border-radius:11px;padding:11px 20px;color:var(--navy)">
                    <i class="bi bi-plus-circle-fill me-2"></i>Ajouter un médicament
                </a>

                <a href="{{ route('pharmacien.medicaments.index') }}" class="btn-glass">
                    <i class="bi bi-capsule me-2"></i>Gérer mon stock
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="hero-date">
                <div class="d">{{ now()->locale('fr')->translatedFormat('l d F Y') }}</div>
                <div class="t" id="clock">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Indicateurs ===== --}}
<div class="row g-4 mt-1">

    <div class="col-xl-3 col-md-6 fade-up" style="--d:1">
        <div class="pf-card hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Références en stock</div>
                    <div class="stat-value" data-count="{{ $total }}">{{ $total }}</div>
                </div>
                <div class="stat-icon ic-teal"><i class="bi bi-capsule"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-up" style="--d:2">
        <div class="pf-card hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Unités disponibles</div>
                    <div class="stat-value" data-count="{{ $units }}">{{ $units }}</div>
                </div>
                <div class="stat-icon ic-navy"><i class="bi bi-boxes"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-up" style="--d:3">
        <div class="pf-card hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Stock faible</div>
                    <div class="stat-value" data-count="{{ $low }}">{{ $low }}</div>
                </div>
                <div class="stat-icon ic-warn"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-up" style="--d:4">
        <div class="pf-card hover p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Ruptures de stock</div>
                    <div class="stat-value" data-count="{{ $out }}">{{ $out }}</div>
                </div>
                <div class="stat-icon ic-danger"><i class="bi bi-x-octagon"></i></div>
            </div>
        </div>
    </div>

</div>

@if($total === 0)

    <div class="pf-card mt-4 fade-up" style="--d:5">
        <div class="empty-state">
            <div class="big-ic"><i class="bi bi-capsule-pill"></i></div>
            <h4 class="fw-bold" style="color:var(--navy)">Votre stock est vide pour le moment</h4>
            <p class="text-muted mb-4">
                Ajoutez vos premiers médicaments pour que les patients puissent<br>
                vous trouver dans les résultats de recherche.
            </p>
            <a href="{{ route('pharmacien.medicaments.create') }}" class="btn-teal">
                <i class="bi bi-plus-circle me-2"></i>Ajouter mon premier médicament
            </a>
        </div>
    </div>

@else

    @if($stale > 0)
        <div class="insight mt-4 fade-up" style="--d:5">
            <i class="bi bi-clock-history"></i>
            <div class="flex-grow-1">
                <strong>{{ $stale }} référence(s)</strong> n'ont pas été mises à jour depuis plus de 30 jours.
                Un stock à jour inspire confiance aux patients.
            </div>
            <a href="{{ route('pharmacien.medicaments.index') }}" class="btn btn-sm btn-warning fw-semibold">Mettre à jour</a>
        </div>
    @endif

    {{-- ===== Graphiques ===== --}}
    <div class="row g-4 mt-1">

        <div class="col-lg-8 fade-up" style="--d:6">
            <div class="pf-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title-pf"><i class="bi bi-bar-chart-fill me-2 text-info"></i>Médicaments les mieux approvisionnés</h6>
                    <span class="pill pill-info">Top {{ $chartLabels->count() }}</span>
                </div>
                <div class="chart-box">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up" style="--d:7">
            <div class="pf-card p-4 h-100">
                <h6 class="card-title-pf mb-3"><i class="bi bi-pie-chart-fill me-2 text-info"></i>Santé du stock</h6>

                <div class="chart-box" style="height:200px">
                    <canvas id="donutChart"></canvas>
                    <div class="donut-center">
                        <div>
                            <div class="v">{{ $health }}%</div>
                            <div class="l">disponible</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 small">
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-dot" style="background:#16A6A0"></span>Disponible</span>
                        <strong>{{ $available }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="legend-dot" style="background:#F5A524"></span>Stock faible</span>
                        <strong>{{ $low }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><span class="legend-dot" style="background:#E5484D"></span>Rupture</span>
                        <strong>{{ $out }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== Listes ===== --}}
    <div class="row g-4 mt-1">

        <div class="col-lg-6 fade-up" style="--d:8">
            <div class="pf-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title-pf"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>À réapprovisionner</h6>
                    <a href="{{ route('pharmacien.medicaments.index') }}" class="small text-decoration-none" style="color:var(--teal)">Tout voir</a>
                </div>

                @forelse($alerts as $s)
                    <div class="list-row">
                        <div>
                            <div class="n">{{ $s->medicament->name }}</div>
                            <div class="s">{{ $s->medicament->dosage ?? '' }} {{ $s->medicament->form ?? '' }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($s->declared_quantity <= 0)
                                <span class="pill pill-out"><i class="bi bi-x-circle-fill"></i>Rupture</span>
                            @else
                                <span class="pill pill-low"><i class="bi bi-exclamation-triangle-fill"></i>{{ $s->declared_quantity }} restant(s)</span>
                            @endif
                            <a href="{{ route('pharmacien.medicaments.edit', $s->medicament_id) }}"
                               class="btn btn-sm btn-soft" style="padding:6px 12px" title="Modifier le stock">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-emoji-smile fs-2 d-block mb-2" style="color:var(--teal)"></i>
                        Aucune alerte : tout votre stock est bien approvisionné !
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-6 fade-up" style="--d:9">
            <div class="pf-card p-4 h-100">
                <h6 class="card-title-pf mb-2"><i class="bi bi-clock-history me-2 text-info"></i>Dernières mises à jour</h6>

                @foreach($recent as $s)
                    <div class="list-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="timeline-dot"><i class="bi bi-arrow-repeat"></i></div>
                            <div>
                                <div class="n">{{ $s->medicament->name }}</div>
                                <div class="s">
                                    {{ $s->updated_stock_at ? $s->updated_stock_at->locale('fr')->diffForHumans() : 'Jamais mis à jour' }}
                                </div>
                            </div>
                        </div>
                        <span class="pill {{ $s->declared_quantity <= 0 ? 'pill-out' : ($s->declared_quantity <= 5 ? 'pill-low' : 'pill-ok') }}">
                            {{ $s->declared_quantity }} unité(s)
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endif

@endsection

@push('scripts')

<script>
    // Horloge en direct
    (function () {
        const el = document.getElementById('clock');
        if (!el) return;
        setInterval(function () {
            const d = new Date();
            el.textContent = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
        }, 15000);
    })();
</script>

@if($total > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#82919B';

        const ctxBar = document.getElementById('barChart').getContext('2d');
        const grad = ctxBar.createLinearGradient(0, 0, 0, 270);
        grad.addColorStop(0, '#16A6A0');
        grad.addColorStop(1, '#123B4A');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Quantité',
                    data: @json($chartValues),
                    backgroundColor: grad,
                    borderRadius: 10,
                    maxBarThickness: 46
                }]
            },
            options: {
                maintainAspectRatio: false,
                animation: { duration: 1100, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#EEF3F5' } }
                }
            }
        });

        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Disponible', 'Stock faible', 'Rupture'],
                datasets: [{
                    data: [{{ $available }}, {{ $low }}, {{ $out }}],
                    backgroundColor: ['#16A6A0', '#F5A524', '#E5484D'],
                    borderWidth: 4,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '72%',
                animation: { animateRotate: true, duration: 1200 },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endif

@endpush
