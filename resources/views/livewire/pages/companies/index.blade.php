<?php

use App\Models\Company;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return [
            'companies' => Company::latest()->get(),
        ];
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Companies') }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('companies.kanban') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Kanban Board') }}
                </a>
                <a href="{{ route('companies.create') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('New Company') }}
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
                                <th class="py-2 text-start">{{ __('Company name') }}</th>
                                <th class="py-2 text-start">{{ __('Status') }}</th>
                                <th class="py-2 text-start">{{ __('Email') }}</th>
                                <th class="py-2 flex justify-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr class="border-b">
                                    <td class="py-4">{{ $company->name }}</td>
                                    <td class="py-4">{{ $company->status->label() }}</td>
                                    <td class="py-4">
                                        @if ($company->email)
                                            <a href="mailto:{{ $company->email }}" class="text-gray-800 underline">{{ $company->email }}</a>
                                        @endif
                                    </td>
                                    <td class="py-4 text-end">
                                        <div class="flex gap-2 justify-end">
                                            <a href="{{ route('companies.edit', $company) }}" wire:navigate class="px-3 py-1 bg-gray-500 text-white rounded-md text-sm">
                                                {{ __('Edit') }}
                                            </a>
                                            <button
                                                wire:click="delete({{ $company->id }})"
                                                wire:confirm="Are you sure you want to delete this company?"
                                                class="px-3 py-1 bg-red-600 text-white rounded-md text-sm">
                                                {{ __('Delete') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-gray-400">{{ __('Company table is empty.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>