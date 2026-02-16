@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            @include('tasks.dasboardCard')
            @can('viewAny', App\Models\User::class)
                @include('users.dashboardCard')
            @endcan
        </div>
        <div class="row mt-4">
            <h3>ApexCharts Examples</h3>
            <div class="col-md-6">

                <div id="barChart"></div>
            </div>
            <div class="col-md-6">
                <div id="pieChart"></div>

            </div>
            {{-- <div class="col-md-4">
                <div id="donutChart" style="margin-top:40px;"></div>

            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ----- Bar Chart -----
    var barOptions = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Votes', data: [10, 20, 15, 30, 25] }],
        xaxis: { categories: ['Red','Blue','Yellow','Green','Purple'] }
    };
    var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
    barChart.render();

    // ----- Pie Chart -----
    var pieOptions = {
        chart: { type: 'pie', height: 350 },
        series: [44, 55, 13],
        labels: ['Red','Blue','Yellow']
    };
    var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
    pieChart.render();

    // ----- Donut Chart -----
    var donutOptions = {
        chart: { type: 'donut', height: 350 },
        series: [40, 25, 35],
        labels: ['Apple','Samsung','Xiaomi']
    };
    var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
    donutChart.render();
</script>
@endpush
