<?php
namespace App\Http\Controllers;

use App\Models\SpecialSubscription;
use Illuminate\Http\Request;

class SpecialSubscriptionController extends Controller
{
    /**
     * Display a listing of the special subscriptions.
     */
    public function index()
    {
        $subscriptions = SpecialSubscription::all();
        return view('backend.special_subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new special subscription.
     */
    public function create()
    {
        return view('backend.special_subscriptions.create');
    }

    /**
     * Store a newly created special subscription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subscription_type' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        SpecialSubscription::create($request->only(['subscription_type', 'discount', 'amount', 'description']));
        return redirect()->route('admin.special_subscription.index')->with('success', 'Special Subscription created successfully.');
    }

    /**
     * Show the form for editing the specified special subscription.
     */
    public function edit($id)
    {
        $subscription = SpecialSubscription::findOrFail($id);
        return view('backend.special_subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified special subscription in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'subscription_type' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $subscription = SpecialSubscription::findOrFail($id);
        $subscription->update($request->only(['subscription_type', 'discount', 'amount', 'description']));
        return redirect()->route('admin.special_subscription.index')->with('success', 'Special Subscription updated successfully.');
    }

    /**
     * Remove the specified special subscription from storage.
     */
    public function destroy($id)
    {
        $subscription = SpecialSubscription::findOrFail($id);
        $subscription->delete();
        return redirect()->route('admin.special_subscription.index')->with('success', 'Special Subscription deleted successfully.');
    }
}
