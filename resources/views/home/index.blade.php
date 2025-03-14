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
                    <div id="PalletEngineAssy" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletTransmissionAssy" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletFrontAxle" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletDifferentialCase" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletFlangeCompanion" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="PalletFrontAxleAssy" style="height: 270px; max-width: 920px; margin: 0px auto;"></div>
                </div>
            </div>
        </div>




    </div>

    <div class="card">
        <div  class="card-header">
            <h3 style="margin-bottom: -10px" class="card-title">List of Slow Movement Pallet</h3>
          </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableUser" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No. Delivery</th>
                            <th>No Pallet</th>
                            <th>Type Pallet</th>
                            <th>Storage</th>
                            <th>Status</th>
                            <th>Days Idle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($slowPallet as $data)
                        <tr>
                            <td>{{ $data->no_delivery }}</td>
                            <td>{{ $data->no_pallet }}</td>
                            <td>{{ $data->type_pallet }}</td>
                            <td>{{ $data->destination }}</td>
                            <td> @if($data->status == 1)
                                <!-- Button for active status -->
                                <button class="btn btn-success btn-sm">
                                    Active
                                </button>
                            @else
                                <!-- Button for disposal status -->
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-x"></i> Done
                                </button>
                            @endif</td>
                            <td>{{ $data->days_since_last_movement }} Days</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

         // Chart for Differential Case
    var chartDifferentialCase = new CanvasJS.Chart("PalletDifferentialCase", {
        theme: "light2",
        exportEnabled: true,
        animationEnabled: true,
        title: {
            text: "Pallet Differential Case"
        },
        data: [{
            type: "pie",
            startAngle: 25,
            toolTipContent: "<b>{label}</b>: {y}",
            showInLegend: "true",
            legendText: "{label}",
            indexLabelFontSize: 16,
            indexLabel: "{label} - {y}",
            dataPoints: {!! $differentialCasePieData !!}
        }]
    });

    // Chart for Flange Companion
    var chartFlangeCompanion = new CanvasJS.Chart("PalletFlangeCompanion", {
        theme: "light2",
        exportEnabled: true,
        animationEnabled: true,
        title: {
            text: "Pallet Flange Companion"
        },
        data: [{
            type: "pie",
            startAngle: 25,
            toolTipContent: "<b>{label}</b>: {y}",
            showInLegend: "true",
            legendText: "{label}",
            indexLabelFontSize: 16,
            indexLabel: "{label} - {y}",
            dataPoints: {!! $flangeCompanionPieData !!}
        }]
    });
     // Chart for Front Axle Assy
     var chartFrontAxleAssy = new CanvasJS.Chart("PalletFrontAxleAssy", {
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        exportEnabled: true,
        animationEnabled: true,
        title: {
            text: "Pallet Front Axle Assy"
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
            dataPoints: {!! $frontAxleAssyPieData !!} // Ensure the data is correctly injected here
        }]
    });

        chartEngine.render();
        chartTransmission.render();
        chartFrontAxle.render();
        chartDifferentialCase.render();
        chartFlangeCompanion.render();
        chartFrontAxleAssy.render();
    }
</script>
<script>
    $(document).ready(function() {
      var table = $("#tableUser").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
    });
  </script>




</main>
@endsection
