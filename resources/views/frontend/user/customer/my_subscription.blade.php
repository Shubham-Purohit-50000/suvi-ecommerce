@extends('frontend.layouts.user_panel')

@section('panel_content')
    <form id="purchase-subscription-form" method="POST" action="{{ route('purchase_special_subscription') }}">
        @csrf
        <input type="hidden" name="special_subscription_id" id="special_subscription_id">
    </form>
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3 fs-18 fw-700 text-dark">{{ translate('Special Subscriptions') }}</h3>
        </div>
        @foreach($special_subscriptions as $subscription)
            <div class="col-md-4 mb-4">
                <a href="#" class="card h-100 text-decoration-none @if(in_array($subscription->id, $active_subscriptions)) border-primary @else border-light @endif" style="transition: box-shadow .2s; box-shadow: 0 2px 8px rgba(0,0,0,0.04); cursor:pointer;">
                    <div class="card-body">
                        <h5 class="card-title mb-2 fs-16 fw-700 text-dark">{{ $subscription->subscription_type }}</h5>
                        <p class="card-text mb-1 fs-14 text-secondary">{{ translate('Discount:') }} <span class="fw-700 text-primary">{{ $subscription->discount }}%</span></p>
                        <p class="card-text mb-1 fs-14 text-secondary">{{ translate('Amount:') }} <span class="fw-700 text-success">{{ single_price($subscription->amount) }}</span></p>
                        @if($subscription->description)
                            <p class="card-text mb-1 fs-13 text-muted">{{ $subscription->description }}</p>
                        @endif
                        @if(in_array($subscription->id, $active_subscriptions))
                            <span class="badge badge-success" style="width:auto;">{{ translate('Active') }}</span>
                        @else
                            <button type="button" class="btn btn-primary btn-sm mt-2" onclick="event.stopPropagation(); selectSubscription({{ $subscription->id }})">{{ translate('Purchase') }}</button>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@section('script')
<script>
    function selectSubscription(id) {
        document.getElementById('special_subscription_id').value = id;
        document.getElementById('purchase-subscription-form').submit();
    }
</script>
@endsection
