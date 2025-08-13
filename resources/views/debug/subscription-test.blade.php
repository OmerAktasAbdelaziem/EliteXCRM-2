<!DOCTYPE html>
<html>
<head>
    <title>Subscription Test</title>
</head>
<body>
    <h1>Subscription Status Test</h1>
    
    @if(isset($subscription_inactive))
        <p><strong>Subscription Inactive Variable:</strong> {{ $subscription_inactive ? 'TRUE' : 'FALSE' }}</p>
        
        @if($subscription_inactive)
            <div style="background: red; color: white; padding: 20px;">
                <h2>SUBSCRIPTION IS INACTIVE</h2>
                <p>The overlay should show on actual pages.</p>
            </div>
        @else
            <div style="background: green; color: white; padding: 20px;">
                <h2>SUBSCRIPTION IS ACTIVE</h2>
                <p>No overlay should show on actual pages.</p>
            </div>
        @endif
    @else
        <div style="background: orange; color: white; padding: 20px;">
            <h2>SUBSCRIPTION VARIABLE NOT SET</h2>
            <p>The middleware is not working or not applied to this route.</p>
        </div>
    @endif
    
    <hr>
    <a href="{{ route('client.index') }}">Go to Client Index</a>
</body>
</html>
