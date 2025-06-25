@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">Add Special Subscription</h5>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.special_subscription.store') }}" method="POST">
            @csrf
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Subscription Type</label>
                <div class="col-md-9">
                    <input type="text" name="subscription_type" class="form-control" required value="{{ old('subscription_type') }}">
                    @error('subscription_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Discount (%)</label>
                <div class="col-md-9">
                    <input type="number" name="discount" class="form-control" required min="0" max="100" step="0.01" value="{{ old('discount') }}">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Amount</label>
                <div class="col-md-9">
                    <input type="number" name="amount" class="form-control" required min="0" step="0.01" value="{{ old('amount') }}">
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Description</label>
                <div class="col-md-9">
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
