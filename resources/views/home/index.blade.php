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
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header text-dark">Pallet Engine</div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="enginePieChart" width="100%" height="50"></canvas></div>
                </div>
                <div class="card-footer small text-muted">
                    <p style="color: black" >Updated today at {{ now()->format('h:i A') }}</p>
                    
                    @foreach(json_decode($enginePieData, true) as $destination => $count)
                    <div class="row">
                        <div class="col-md-3">
                            <p style="color: black">{{ $destination }}</p>
                        </div>
                        <div class="col-md-9">
                            <p style="color: black">: {{ $count }}</p>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>


        <!-- Pie chart for 'Transmission' -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header text-dark">Pallet TM-Assy</div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="transmissionPieChart" width="100%" height="50"></canvas></div>
                </div>
                <div class="card-footer small text-muted">
                    <p style="color: black" >Updated today at {{ now()->format('h:i A') }}</p>

                    @foreach(json_decode($transmissionPieData, true) as $destination => $count)
                    <div class="row">
                        <div class="col-md-3">
                            <p style="color: black">{{ $destination }}</p>
                        </div>
                        <div class="col-md-9">
                            <p style="color: black">: {{ $count }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pie chart for 'FA' -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header text-dark">Pallet FA</div>
                <div class="card-body">
                    <div class="chart-pie"><canvas id="faPieChart" width="100%" height="50"></canvas></div>
                </div>
                <div class="card-footer small text-muted">
                    <p style="color: black" >Updated today at {{ now()->format('h:i A') }}</p>

                    @foreach(json_decode($faPieData, true) as $destination => $count)
                        <div class="row">
                            <div class="col-md-3">
                                <p style="color: black">{{ $destination }}</p>
                            </div>
                            <div class="col-md-9">
                                <p style="color: black">: {{ $count }}</p>
                            </div>
                        </div>
                       
                    @endforeach
                </div>
            </div>
        </div>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for 'Engine' Pie Chart
    var enginePieData = {!! $enginePieData !!};

    // Data for 'Transmission' Pie Chart
    var transmissionPieData = {!! $transmissionPieData !!};

    // Data for 'FA' Pie Chart
    var faPieData = {!! $faPieData !!};

    // Mapping of destinations to colors
    var destinationColors = {
        'MKM': '#FF5733',
        'KTBSP': '#FFC300',
        'KRM': '#33FF57',
        'TJU': '#3385FF',
    };

    // Initialize 'Engine' Pie Chart
    var enginePieChart = new Chart(document.getElementById('enginePieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: Object.keys(enginePieData),
            datasets: [{
                data: Object.values(enginePieData),
                backgroundColor: Object.keys(enginePieData).map(destination => destinationColors[destination]),
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
                backgroundColor: Object.keys(transmissionPieData).map(destination => destinationColors[destination]),
            }],
        },
    });

    // Initialize 'FA' Pie Chart
    var faPieChart = new Chart(document.getElementById('faPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: Object.keys(faPieData),
            datasets: [{
                data: Object.values(faPieData),
                backgroundColor: Object.keys(faPieData).map(destination => destinationColors[destination]),
            }],
        },
    });
</script>


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
</script>

</main>
@endsection
