<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Country</th>
        <th>City</th>
        <th>Address</th>
        <th>Rate</th>
    </tr>
    </thead>
    <tbody>
    @foreach($providers as $provider)
        <tr>
            <td>{{ $provider->id }}</td>
            <td>{{ $provider->first_name }}</td>
            <td>{{ $provider->last_name }}</td>
            <td>{{ $provider->email }}</td>
            <td>{{ $provider->phone }}</td>
            <td>{{ $provider->country }}</td>
            <td>{{ $provider->city }}</td>
            <td>{{ $provider->address }}</td>
            <td>{{ $provider->rate }}</td>
        </tr>
    @endforeach
    </tbody>
</table>