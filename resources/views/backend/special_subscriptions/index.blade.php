@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">Special Subscriptions</h5>
    <a href="{{ route('admin.special_subscription.create') }}" class="btn btn-primary float-right">Add New</a>
</div>
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subscription Type</th>
                    <th>Discount (%)</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $key => $subscription)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $subscription->subscription_type }}</td>
                    <td>{{ $subscription->discount }}</td>
                    <td>{{ single_price($subscription->amount) }}</td>
                    <td>{{ $subscription->description }}</td>
                    <td>
                        <a href="{{ route('admin.special_subscription.edit', $subscription->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.special_subscription.destroy', $subscription->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
