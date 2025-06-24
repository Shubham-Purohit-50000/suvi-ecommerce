@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{translate('General Discount Settings')}}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('general_discount.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Enable General Discount')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="general_discount">
                                <input type="checkbox" name="general_discount_active" value="1" {{ (json_decode(get_setting('general_discount'),true)['active'] ?? 0) == 1 ? 'checked' : '' }}>
                                <small>{{ translate('Check to enable the general discount feature.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Discount Percentage')}}</label>
                            <div class="col-sm-9">
                                <input type="number" name="general_discount_percentage" class="form-control" min="0" max="100" value="{{ json_decode(get_setting('general_discount'),true)['percentage'] ?? 0 }}">
                                <small>{{ translate('Set the discount percentage for users who add 2 or more different products to their cart.') }}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
