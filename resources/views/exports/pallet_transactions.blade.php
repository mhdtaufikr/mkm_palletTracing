<!DOCTYPE html>
<html>
<head>
    <title>Pallet Transactions</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No Delivery</th>
                <th>Destination</th>
                <th>Total Pallets</th>
                <th>Pallet Numbers</th>
                <th>Type of Pallet</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summaries as $summary)
                <tr>
                    <td>{{ $summary->no_delivery }}</td>
                    <td>{{ $summary->destination }}</td>
                    <td>{{ $summary->total_pallets }}</td>
                    <td>{{ $summary->pallet_numbers }}</td>
                    <td>{{ $summary->type_pallet }}</td>
                    <td>{{ $summary->date }}</td>
                    <td>{{ $summary->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
