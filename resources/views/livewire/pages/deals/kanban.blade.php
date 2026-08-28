<?php

use App\Models\Deal;
use App\Enums\DealStatus;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $deals = Deal::all();

        $grouped = [];
        foreach (DealStatus::cases() as $status) {
            $grouped[$status->value] = $deals->where('status', $status)->values();
        }

        return [
            'statuses' => DealStatus::cases(),
            'grouped' => $grouped,
        ];
    }

    public function moveDeal($dealId, $status): void
    {
        Deal::find($dealId)->update(['status' => $status]);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Deals - Kanban Board') }}</h2>
            <a href="{{ route('deals.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
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
                                    group: 'deals',
                                    animation: 150,
                                    filter: '.no-drag',
                                    onAdd: (evt) => {
                                        $wire.moveDeal(evt.item.dataset.id, evt.to.dataset.status);
                                    },
                                });
                            "
                        >
                            @foreach ($grouped[$status->value] as $deal)
                                <div data-id="{{ $deal->id }}" class="bg-gray-800 text-white px-4 py-3 rounded shadow cursor-move">
                                    <div class="mb-1">{{ $deal->title }}</div>

                                    <a href="{{ route('offers.create', ['deal_id' => $deal->id]) }}"
                                        wire:navigate
                                        class="no-drag shrink-0 bg-indigo-500 hover:bg-indigo-600 text-white text-xs px-2 py-1 rounded"
                                        title="{{ __('Create Offer for this deal') }}" 
                                    >
                                        + {{ __('Create Offer') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>