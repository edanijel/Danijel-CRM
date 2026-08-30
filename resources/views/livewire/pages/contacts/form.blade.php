<?php

use App\Models\Contact;
use App\Models\Company;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Enums\ContactStatus;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component
{
    public ?Contact $contact = null;
    public string $title = '';

    public string $company_id = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $position = '';
    public string $status = '';

    public function with(): array
    {
        return [
            'companies' => Company::latest()->get(),
            'statuses' => ContactStatus::cases(),
        ];
    }

    public function mount(?Contact $contact = null): void
    {
        $this->contact = $contact;
        $this->title = $contact ? __('Edit Contact') : __('Add New Contact');

        if ($contact) {
            $this->first_name = $contact->first_name;
            $this->last_name = $contact->last_name;
            $this->company_id = $contact->company_id ?? '';
            $this->email = $contact->email ?? '';
            $this->phone = $contact->phone ?? '';
            $this->position = $contact->position ?? '';
            $this->status = $contact->status->value;
        } else {
            $this->status = ContactStatus::cases()[0]->value;
        }
    }


    public function save(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'position' => ['nullable'],
            'status' => ['required', Rule::enum(ContactStatus::class)],
        ]);

        $validated['company_id'] = $validated['company_id'] ?: null;

        if ($this->contact) {
            $this->contact->update($validated);
            session()->flash('success', __('Contact ":name" successfully updated.', ['name' => $this->contact->first_name .' '. $this->contact->last_name]));
        } else {
            $contact = Contact::create($validated);
            session()->flash('success', __('Contact ":name" successfully created.', ['name' => $contact->first_name .' '. $contact->last_name]));
        }

        $this->redirect(route('contacts.index'), navigate: true);
    }

};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>
            <a href="{{ route('contacts.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form wire:submit="save" class="max-w-xl">
                    <!-- First Name -->
                    <div>
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" autofocus />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <!-- Last Name -->
                    <div class="mt-4">
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" autofocus />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <!-- Company -->
                    <div class="mt-4">
                        <x-input-label for="company" :value="__('Company')" />
                        <select wire:model="company_id" id="company" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">---</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company')" class="mt-2" />
                    </div>
                    
                    <!-- email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- phone -->
                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>


                    <!-- position -->
                    <div class="mt-4">
                        <x-input-label for="position" :value="__('Position')" />
                        <x-text-input wire:model="position" id="position" class="block mt-1 w-full" type="text" name="position" />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <x-input-label for="status" :value="__('Status')" />
                        <select wire:model="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($statuses as $case)
                                <option value="{{ $case->value }}">{{ $case->label() }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    @if ($errors->any())
                        <div class="mt-4 mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul class="list-disc pl-5 max-w-7xl mx-auto">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-6">
                        <x-primary-button>
                            {{ __('Save changes') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>