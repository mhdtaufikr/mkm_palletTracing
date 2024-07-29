<!DOCTYPE html>
<html>
<head>
    <title>Pallet Transaction Summary</title>
</head>
<body>
    <h1>Pallet Transaction Summary</h1>
    @foreach ($summaries as $no_delivery => $summaryGroup)
        <h2>No Delivery: {{ $no_delivery }}</h2>
        <p>Destination: {{ $summaryGroup->first()->destination }}</p>
        <p>Total Pallets: {{ $summaryGroup->count() }}</p>
        <p>Pallet Numbers: {{ $summaryGroup->pluck('no_pallet')->join(', ') }}</p>
        <p>Type of Pallet: {{ $summaryGroup->first()->type_pallet }}</p>
        <p>Date: {{ $summaryGroup->first()->date }}</p>
        <p>Status: {{ $summaryGroup->first()->status }}</p>
        <hr>
    @endforeach
</body>
</html>
