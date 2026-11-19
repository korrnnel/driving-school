<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Panel Instruktora - Podsumowanie</h2>
    </x-slot>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body py-4">
                    <h4 class="mb-1">Witaj na zmianie, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 text-white-50">Twoja rola w systemie: <strong>{{ Auth::user()->role_translated }}</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Twoje jazdy na dzisiaj</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $todayLessons }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Zrealizowane w tym miesiącu</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $monthLessons }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Twoi unikalni kursanci</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $uniqueStudents }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold">Statusy Twoich jazd</h6>
                </div>
                <div class="card-body">
                    <canvas id="instructorStatusChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="mb-0 fw-bold">Ilość Twoich jazd (Ostatnie 7 dni)</h6>
                </div>
                <div class="card-body">
                    <canvas id="instructorLessonsChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxStatus = document.getElementById('instructorStatusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: {!! $instructorStatusesLabels !!},
                    datasets: [{
                        data: {!! $instructorStatusesData !!},
                        backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#dc3545'],
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

            const ctxLessons = document.getElementById('instructorLessonsChart').getContext('2d');
            new Chart(ctxLessons, {
                type: 'bar',
                data: {
                    labels: {!! $instructorDatesJson !!},
                    datasets: [{
                        label: 'Liczba Twoich jazd',
                        data: {!! $instructorLessonsJson !!},
                        backgroundColor: '#198754',
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
