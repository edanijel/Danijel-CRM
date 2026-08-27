<?php

use App\Models\Company;
use App\Enums\CompanyStatus;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $companies = Company::all();

        $grouped = [];
        foreach (CompanyStatus::cases() as $status) {
            $grouped[$status->value] = $companies->where('status', $status)->values();
        }

        return [
            'statuses' => CompanyStatus::cases(),
            'grouped' => $grouped,
        ];
    }

    public function moveCompany($companyId, $status): void
    {
        Company::find($companyId)->update(['status' => $status]);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Companies - Kanban Board') }}</h2>
            <a href="{{ route('companies.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                    group: 'companies',
                                    animation: 150,
                                    onAdd: (evt) => {
                                        $wire.moveCompany(evt.item.dataset.id, evt.to.dataset.status);
                                    },
                                });
                            "
                        >
                            @foreach ($grouped[$status->value] as $company)
                                <div data-id="{{ $company->id }}" class="bg-gray-800 text-white px-4 py-2 rounded shadow-mb cursor-move">
                                    {{ $company->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>