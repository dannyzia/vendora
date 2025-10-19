<div>
    <h1>Subscription Tiers</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Product Limit</th>
                <th>Commission Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->price }}</td>
                    <td>{{ $plan->product_limit }}</td>
                    <td>{{ $plan->commission_rate }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
