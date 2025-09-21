<!DOCTYPE html>
<html>
<head>
    <title>Pallet Transaction Summary</title>
</head>
<body>
    <h1>Pallet Transaction Summary</h1>
    <p>Total Transactions: {{ $summaries->count() }}</p>
    @foreach ($summaries->groupBy('destination') as $destination => $group)
        <h2>Destination: {{ $destination }}</h2>
        <p>Total Pallets: {{ $group->sum('total_pallets') }}</p>
    @endforeach
    <p>Detail transactions are attached in the Excel file.</p>
</body>
</html>
