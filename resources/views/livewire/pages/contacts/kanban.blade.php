<?php

use App\Models\Contact;
use App\Enums\ContactStatus;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $contacts = Contact::all();

        $grouped = [];
        foreach (ContactStatus::cases() as $status) {
            $grouped[$status->value] = $contacts->where('status', $status)->values();
        }

        return [
            'statuses' => ContactStatus::cases(),
            'grouped' => $grouped,
        ];
    }

    public function moveContact($contactId, $status): void
    {
        Contact::find($contactId)->update(['status' => $status]);
    }
};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Contact - Kanban Board') }}</h2>
            <a href="{{ route('contacts.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
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
                                    group: 'contacts',
                                    animation: 150,
                                    onAdd: (evt) => {
                                        $wire.moveContact(evt.item.dataset.id, evt.to.dataset.status);
                                    },
                                });
                            "
                        >
                            @foreach ($grouped[$status->value] as $contact)
                                <div data-id="{{ $contact->id }}" class="bg-gray-800 text-white px-4 py-2 rounded shadow cursor-move">
                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>