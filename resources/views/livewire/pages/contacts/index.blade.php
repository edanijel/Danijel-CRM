<?php

use App\Models\Contact;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public ?string $flashMessage = null;

    public function with(): array
    {
        return [
            'contacts' => Contact::latest()->get(),
        ];
    }

    public function delete(Contact $contact): void
    {
        $name = $contact->first_name . ' ' . $contact->last_name;
        $contact->delete();
        $this->flashMessage = __('Contact ":name" deleted successfully.', ['name' => $name]);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Contacts') }}</h2>
            
            <div class="flex gap-3">
                <a href="{{ route('contacts.kanban') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Kanban Board') }}
                </a>
                <a href="{{ route('contacts.create') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('New Contact') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-inline-flash-message :message="$flashMessage" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-gray-500">
                                <th class="py-2 text-start">{{ __('Name') }}</th>
                                <th class="py-2 text-start">{{ __('Company') }}</th>
                                <th class="py-2 text-start">{{ __('Status') }}</th>
                                <th class="py-2 text-start">{{ __('Email') }}</th>
                                <th class="py-2 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr class="border-b">
                                    <td class="py-4">{{ $contact->first_name }} {{ $contact->last_name }}</td>
                                    <td class="py-4">{{ $contact->company?->name }}</td>
                                    <td class="py-4">{{ $contact->status->label() }}</td>
                                    <td class="py-4">
                                        @if ($contact->email)
                                            <a href="mailto:{{ $contact->email }}" class="text-gray-800 underline">{{ $contact->email }}</a>
                                        @endif
                                    </td>
                                    <td class="py-4 text-end">
                                        <div class="flex gap-2 justify-end">
                                            <a href="{{ route('contacts.edit', $contact) }}" wire:navigate class="px-3 py-1 bg-gray-500 text-white rounded-md text-sm">
                                                {{ __('Edit') }}
                                            </a>
                                            <button
                                                wire:click="delete({{ $contact->id }})"
                                                wire:confirm="Are you sure you want to delete this contact?"
                                                class="px-3 py-1 bg-red-600 text-white rounded-md text-sm">
                                                {{ __('Delete') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-gray-400">{{ __('Contacts table is empty.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>