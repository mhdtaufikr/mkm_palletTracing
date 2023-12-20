@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-2">
                        <h1 class="page-header-title">
                            <label id="lblGreetings"></label>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
<div class="container-xl px-4 mt-n10">
    <div class="row">

        <!-- Pie chart for 'Engine' -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header text-dark">Pallet Engine</div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="enginePieChart" width="100%" height="50"></canvas></div>
                </div>
               <div class="card-footer small text-muted">Updated today at {{ now()->format('h:i A') }}</div>

            </div>
        </div>

        <!-- Pie chart for 'Transmission' -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header text-dark">Pallet Transmission</div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="transmissionPieChart" width="100%" height="50"></canvas></div>
                </div>
               <div class="card-footer small text-muted">Updated today at {{ now()->format('h:i A') }}</div>

            </div>
        </div>

    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var myDate = new Date();
        var hrs = myDate.getHours();

        var greet;

        if (hrs < 12)
            greet = 'Good Morning';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Good Afternoon';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Good Evening';

        document.getElementById('lblGreetings').innerHTML =
            '<b>' + greet + '</b> and welcome to Pallet Tracing!';

        // Data for 'Engine' Pie Chart
        var enginePieData = {!! $enginePieData !!};

        // Data for 'Transmission' Pie Chart
        var transmissionPieData = {!! $transmissionPieData !!};

        // Initialize 'Engine' Pie Chart
        var enginePieChart = new Chart(document.getElementById('enginePieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: Object.keys(enginePieData),
                datasets: [{
                    data: Object.values(enginePieData),
                    backgroundColor: ['#FF5733', '#FFC300', '#33FF57'], // Set your preferred colors
                }],
            },
        });

        // Initialize 'Transmission' Pie Chart
        var transmissionPieChart = new Chart(document.getElementById('transmissionPieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: Object.keys(transmissionPieData),
                datasets: [{
                    data: Object.values(transmissionPieData),
                    backgroundColor: ['#FF5733', '#FFC300', '#33FF57'], // Set your preferred colors
                }],
            },
        });
    </script>
</main>
@endsection
