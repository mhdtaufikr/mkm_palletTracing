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

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletEngineAssy" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletTransmissionAssy" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletFrontAxle" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>


    </div>
</div>


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


<script>
    window.onload = function () {
        var chartEngine = new CanvasJS.Chart("PalletEngineAssy", {
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            exportEnabled: true,
            animationEnabled: true,
            title: {
                text: "Pallet Engine Assy"
            },
            data: [{
                type: "pie",
                startAngle: 25,
                toolTipContent: "<b>{label}</b>: {y}",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - {y}",
                indexLabelFontColor: "#000000", // Default color for index labels
                dataPoints: {!! $enginePieData !!}
            }]
        });

        var chartTransmission = new CanvasJS.Chart("PalletTransmissionAssy", {
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            exportEnabled: true,
            animationEnabled: true,
            title: {
                text: "Pallet Transmission Assy"
            },
            data: [{
                type: "pie",
                startAngle: 25,
                toolTipContent: "<b>{label}</b>: {y}",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - {y}",
                indexLabelFontColor: "#000000", // Default color for index labels
                dataPoints: {!! $transmissionPieData !!}
            }]
        });

        var chartFrontAxle = new CanvasJS.Chart("PalletFrontAxle", {
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            exportEnabled: true,
            animationEnabled: true,
            title: {
                text: "Pallet Front Axle"
            },
            data: [{
                type: "pie",
                startAngle: 25,
                toolTipContent: "<b>{label}</b>: {y}",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - {y}",
                indexLabelFontColor: "#000000", // Default color for index labels
                dataPoints: {!! $faPieData !!}
            }]
        });

        chartEngine.render();
        chartTransmission.render();
        chartFrontAxle.render();
    }
</script>





</main>
@endsection
