<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Dashboard - Podsumowanie</h2>
    </x-slot>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body py-4">
                    <h4 class="mb-1">Witaj w systemie, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 text-white-50">Twoja rola w systemie: <strong>{{ Auth::user()->role_translated }}</strong>. Poniżej znajdziesz aktualne statystyki szkoły jazdy.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Aktywni kursanci</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $activeStudents }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Dzisiejsze jazdy</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $todayLessons }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Aktywne pojazdy</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $activeVehicles }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Instruktorzy</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $instructorsCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold">Popularność kategorii</h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold">Ilość zrealizowanych jazd (Ostatnie 7 dni)</h6>
                </div>
                <div class="card-body">
                    <canvas id="lessonsChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: {!! $chartCategoriesLabels !!},
                    datasets: [{
                        data: {!! $chartCategoriesData !!},
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            const ctxLessons = document.getElementById('lessonsChart').getContext('2d');
            new Chart(ctxLessons, {
                type: 'bar',
                data: {
                    labels: {!! $chartDatesJson !!},
                    datasets: [{
                        label: 'Liczba jazd',
                        data: {!! $chartLessonsJson !!},
                        backgroundColor: '#0d6efd',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
