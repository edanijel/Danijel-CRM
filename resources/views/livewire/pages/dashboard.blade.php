<?php

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Offer;
use App\Enums\DealStatus;
use App\Enums\OfferStatus;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $openDeals = Deal::with('company')->whereNotIn('status', [DealStatus::Won, DealStatus::Lost])->latest()->get();
        $activeOffers = Offer::with('company')->whereIn('status', [OfferStatus::Draft, OfferStatus::Sent])->latest()->get();

        return [
            'companiesCount' => Company::count(),
            'contactsCount' => Contact::count(),
            'openDealsCount' => $openDeals->count(),
            'activeOffersCount' => $activeOffers->count(),
            'openDeals' => $openDeals->take(10),
            'activeOffers' => $activeOffers->take(10),
        ];
    }
};
?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('companies.index') }}" wire:navigate class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm text-gray-500">{{ __('Companies') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $companiesCount }}</div>
                </a>
                <a href="{{ route('contacts.index') }}" wire:navigate class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm text-gray-500">{{ __('Contacts') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $contactsCount }}</div>
                </a>
                <a href="{{ route('deals.index') }}" wire:navigate class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm text-gray-500">{{ __('Open Deals') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $openDealsCount }}</div>
                </a>
                <a href="{{ route('offers.index') }}" wire:navigate class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm text-gray-500">{{ __('Active Offers') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $activeOffersCount }}</div>
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">{{ __('Open Deals') }}</h3>
                    <div class="space-y-1">
                        @forelse ($openDeals as $deal)
                            <a href="{{ route('deals.edit', $deal) }}" wire:navigate class="flex justify-between items-center py-2 bg-gray-50 px-3 py-2 hover:bg-gray-100 -mx-2 px-2 rounded">
                                <span>
                                    {{ $deal->title }}
                                    <span class="text-gray-400 text-xs block">{{ $deal->company?->name }}</span>
                                </span>
                                <span class="text-xs text-gray-500">{{ $deal->status->label() }}</span>
                            </a>
                        @empty
                            <p class="text-gray-400 text-sm">{{ __('No open deals.') }}</p>
                        @endforelse
                    </div>
                    @if ($openDealsCount > 5)
                        <a href="{{ route('deals.index') }}" wire:navigate class="block mt-4 text-sm text-indigo-600">{{ __('View all') }} →</a>
                    @endif
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">{{ __('Active Offers') }}</h3>
                    <div class="space-y-1">
                        @forelse ($activeOffers as $offer)
                            <a href="{{ route('offers.edit', $offer) }}" wire:navigate class="flex justify-between items-center bg-gray-50 px-3 py-2 hover:bg-gray-100 -mx-2 px-2 rounded">
                                <span>
                                    {{ $offer->offer_number }} / {{ $offer->title }}
                                    <span class="text-gray-400 text-xs block">{{ $offer->company?->name }}</span>
                                </span>
                                <span class="text-xs text-gray-500">{{ $offer->status->label() }}</span>
                            </a>
                        @empty
                            <p class="text-gray-400 text-sm">{{ __('No active offers.') }}</p>
                        @endforelse
                    </div>
                    @if ($activeOffersCount > 5)
                        <a href="{{ route('offers.index') }}" wire:navigate class="block mt-4 text-sm text-indigo-600">{{ __('View all') }} →</a>
                    @endif
                </div>
            </div>
        </div>

        
    </div>
</div>
