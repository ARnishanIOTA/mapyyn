<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Created_at</th>
        <th>Tansaction Id</th>
        <th>Offer Number</th>
        <th>Client</th>
        <th>Provider</th>
        <th>Price</th>
        <th>Status</th>
        <th>Admin Status</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->created_at }}</td>
            <td>{{ $payment->transaction_id }}</td>
            <td>{{ $payment->offer_id == null ?  $payment->request_offer_id : $payment->offer_id}}</td>
            <td>{{ $payment->client->first_name . ' ' .  $payment->client->last_name}}</td>
            <td>{{ $payment->provider->first_name . ' ' .  $payment->provider->last_name}}</td>
            <td>{{ $payment->price }}</td>
            <td>{{ $payment->status }}</td>
            <td>{{ $payment->admin_status == null ? 'hold' : $payment->admin_status }}</td>
            <td>{{ $payment->notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>