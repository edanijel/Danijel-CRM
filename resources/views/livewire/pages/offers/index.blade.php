<?php

use App\Models\Offer;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return [
            'offers' => Offer::latest()->get(),
        ];
    }

    public function delete(Offer $offer): void
    {
        $offer->delete();
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Offers') }}</h2>
            
            <div class="flex gap-3">
                <a href="{{ route('offers.kanban') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Kanban Board') }}
                </a>
                <a href="{{ route('offers.create') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('New Offer') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-gray-500">
                                <th class="py-2 text-start">{{ __('No') }}</th>
                                <th class="py-2 text-start">{{ __('Title') }}</th>
                                <th class="py-2 text-start">{{ __('Issued') }}</th>
                                <th class="py-2 text-start">{{ __('Valid') }}</th>
                                <th class="py-2 text-start">{{ __('Status') }}</th>
                                <th class="py-2 text-start">{{ __('Total') }}</th>
                                <th class="py-2 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($offers as $offer)
                            <tr class="border-b">
                                <td class="py-4">{{ $offer->offer_number }}</td>
                                <td class="py-4">{{ $offer->title }}</td>
                                <td class="py-4">{{ $offer->offer_issued->format('d.m.Y.') }}</td>
                                <td class="py-4">{{ $offer->offer_valid->format('d.m.Y.') ?? '—' }}</td>
                                <td class="py-4">{{ $offer->status->label() }}</td>
                                <td class="py-4">{{ number_format($offer->total, 2, ',', '.') }} {{ $offer->currency->symbol() }}</td>
                                <td class="py-4 text-end">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('offers.edit', $offer) }}" wire:navigate class="px-3 py-1 bg-gray-500 text-white rounded-md text-sm">
                                            {{ __('Edit') }}
                                        </a>
                                        <a href="{{ route('offers.pdf', $offer) }}" class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs">
                                            {{ __('PDF') }} 
                                        </a>
                                        <button
                                            wire:click="delete({{ $offer->id }})"
                                            wire:confirm="Are you sure you want to delete this offer?"
                                            class="px-3 py-1 bg-red-600 text-white rounded-md text-sm">
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-gray-400">{{ __('Offers table is empty.') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>