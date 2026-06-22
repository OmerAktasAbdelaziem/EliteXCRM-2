<!DOCTYPE html>
<html>
<head>
    <title>Client Export</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
        }
        body, .arabic-text {
            font-family: 'Amiri', 'DejaVu Sans', Arial, sans-serif;
        }        
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; font-size: 12px; }
        th { background: #eee; }
        .logo { height: 80px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom: 10px;">
        @php
            $logoMap = [
                'bnc' => public_path('assets/images/bnc.png'),
                'phoenix' => public_path('assets/images/phoenix.png'),
            ];
            
            
            $logoPath = storage_path('app/public/' . Auth::user()->pipeline->logo);
        @endphp
        @if(is_file($logoPath) && file_exists($logoPath))
            <img src="{{ public_path('storage/'.Auth::User()->pipeline->logo) }}" class="logo" alt="Logo">
        @endif
    </div>
    <table>
        <tr>
            <td><strong >Client Name: </strong>
                <span class="arabic-text">
                    {{ ucfirst(strtolower($client->first_name)) }} {{ ucfirst(strtolower($client->last_name)) }}</td>
                </span>
            <td><strong>Broker ID:   </strong> {{ $client->broker_id }}</td>
            <td><strong>Balance Now: </strong> $ {{ number_format($balanceNow, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total Deposits:   </strong> <span style="color: green;">$ {{ $totalDeposits }}</span></td>
            <td><strong>Total Withdrawals:</strong> <span style="color: red;">- $ {{ $totalWithdrawals }}</span></td>
            <td><strong>Free Margin:     </strong> <span>$ {{ number_format($freeMargin, 2) }}</span></td>
        </tr>
    </table>

    <h3>Account History</h3>
    <table>
        <thead>
            <tr>
                <th>Currency</th><th>Type</th><th>Amount</th><th>Status</th><th>Open Time</th><th>Closed Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($closedOrders as $order)
                <tr>
                    <td>{{ $order->script ?? 'N/A' }}</td>
                    <td>
                        @if($order->order_type == 'Buy')
                            <span style="color: green;">Buy</span>
                        @elseif($order->order_type == 'Sell')
                            <span style="color: red;">Sell</span>
                        @else
                            {{ $order->order_type }}
                        @endif
                    </td>
                    <td>{{ $order->amount }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->time }}</td>
                    <td>{{ $order->closed_at ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No Closed Orders</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Money Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Type</th><th>Amount</th><th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($moneyTrxes as $trx)
                <tr>
                    <td>{{ ucfirst($trx->trx_type) }}</td>
                    <td>
                        @if($trx->trx_type === 'deposit')
                            <span style="color: green;">${{ number_format($trx->amount, 2) }}</span>
                        @elseif($trx->trx_type === 'withdraw')
                            <span style="color: red;">- ${{ number_format($trx->amount, 2) }}</span>
                        @else
                            <span>${{ number_format($trx->amount, 2) }}</span>
                        @endif
                    </td>
                    <td>{{ $trx->time }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No Money Transactions</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
