<?php

use App\Models\Offer;
use App\Enums\OfferStatus;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $offers = Offer::all();

        $grouped = [];
        foreach (OfferStatus::cases() as $status) {
            $grouped[$status->value] = $offers->where('status', $status)->values();
        }

        return [
            'statuses' => OfferStatus::cases(),
            'grouped' => $grouped,
        ];
    }

    public function moveOffer($offerId, $status): void
    {
        Offer::find($offerId)->update(['status' => $status]);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Offers - Kanban Board') }}</h2>
            <a href="{{ route('offers.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 overflow-x-auto" x-data>
                @foreach ($statuses as $status)
                    <div class="p-4 sm:p-8 bg-white shadow-md flex-1 min-w-0">
                        <h3 class="font-semibold text-sm text-gray-600 mb-4">{{ $status->label() }}</h3>

                        <div
                            wire:ignore
                            class="space-y-2 min-h-[100px] border border-dashed border-gray-200 rounded"
                            data-status="{{ $status->value }}"
                            x-init="
                                Sortable.create($el, {
                                    group: 'offers',
                                    animation: 150,
                                    onAdd: (evt) => {
                                        $wire.moveOffer(evt.item.dataset.id, evt.to.dataset.status);
                                    },
                                });
                            "
                        >
                            @foreach ($grouped[$status->value] as $offer)
                                <div data-id="{{ $offer->id }}" class="bg-gray-800 text-white px-4 py-2 rounded shadow cursor-move">
                                    <span class="text-xs">{{ $offer->offer_number }}</span><br />
                                    {{ $offer->title }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>