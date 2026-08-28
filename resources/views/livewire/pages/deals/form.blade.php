<?php

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Company;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Enums\Currency;
use App\Enums\DealStatus;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component
{
    public ?Deal $deal = null;
    public string $pageTitle = '';

    public string $company_id = '';
    public string $contact_id = '';
    public string $title = '';
    public string $description = '';
    public string $value = '';
    public string $currency = '';
    public string $probability = '';
    public string $expected_close_date = '';
    public string $status = '';

    public function with(): array
    {
        return [
            'companies' => Company::latest()->get(),
            'availableContacts' => $this->company_id
                ? Contact::where('company_id', $this->company_id)->get()
                : collect(),
            'currencies' => Currency::cases(),
            'statuses' => DealStatus::cases(),
        ];
    }

    public function mount(?Deal $deal = null): void
    {
        $this->deal = $deal;
        $this->pageTitle = $deal ? __('Edit Deal') : __('Add New Deal');

        if ($deal) {
            $this->company_id = $deal->company_id ?? '';
            $this->contact_id = $deal->contact_id ?? '';
            $this->title = $deal->title;
            $this->description = $deal->description ?? '';
            $this->value = $deal->value ?? '';
            $this->probability = $deal->probability ?? '';
            $this->expected_close_date = $deal->expected_close_date ?? '';
            $this->currency = $deal->currency->value;
            $this->status = $deal->status->value;
        } else {
            $this->currency = Currency::cases()[0]->value;
            $this->status = DealStatus::cases()[0]->value;
        }
    } 


    public function save(): void
    {
        $validated = $this->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'value' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(DealStatus::class)],
        ]);

        $validated['company_id'] = $validated['company_id'] ?: null;
        $validated['contact_id'] = $validated['contact_id'] ?: null;
        $validated['expected_close_date'] = $validated['expected_close_date'] ?: null;

        if ($this->deal) {
            $this->deal->update($validated);
        } else {
            $validated['user_id'] = auth()->id();
            Deal::create($validated);
        }

        $this->redirect(route('deals.index'), navigate: true);
    }

    public function updatedCompanyId(): void
    {
        $this->contact_id = '';
    }

};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $pageTitle }}</h2>

            <div class="flex gap-3">
                @if ($deal)
                    <a href="{{ route('offers.create', ['deal_id' => $deal->id]) }}" wire:navigate class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        {{ __('Create Offer') }}
                    </a>
                @endif
                <a href="{{ route('deals.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form wire:submit="save" class="max-w-xl">
                    <!-- First Name -->
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" name="title" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Last Name -->
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-area wire:model="description" id="description" class="block mt-1 w-full" rows="4" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Company -->
                    <div class="mt-4">
                        <x-input-label for="company" :value="__('Company')" />
                        <select wire:model.live="company_id" id="company" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">---</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>

                    <!-- Contact -->
                    <div class="mt-4">
                        <x-input-label for="contact" :value="__('Contact (Filled based on Company selection)')" />
                        <select wire:model="contact_id" id="contact" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">---</option>
                            @foreach ($availableContacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->first_name }} {{ $contact->last_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                    </div>
                    
                    <!-- value -->
                    <div class="mt-4">
                        <x-input-label for="value" :value="__('Value')" />
                        <x-text-input wire:model="value" id="value" class="block mt-1 w-full" type="text" name="value" />
                        <x-input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>

                    <!-- currency -->
                    <div class="mt-4">
                        <x-input-label for="currency" :value="__('Currency')" />
                        <select wire:model="currency" id="currency" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($currencies as $case)
                                <option value="{{ $case->value }}">{{ $case->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                    </div>


                    <!-- probability -->
                    <div class="mt-4">
                        <x-input-label for="probability" :value="__('Probability (0-100%)')" />
                        <x-text-input wire:model="probability" id="probability" class="block mt-1 w-full" type="number" min="0" max="100" step="1" name="probability" />
                        <x-input-error :messages="$errors->get('probability')" class="mt-2" />
                    </div>

                    <!-- expected_close_date -->
                    <div class="mt-4">
                        <x-input-label for="expected_close_date" :value="__('Expected close date')" />
                        <x-text-input wire:model="expected_close_date" id="expected_close_date" class="block mt-1 w-full" type="date" name="expected_close_date" />
                        <x-input-error :messages="$errors->get('expected_close_date')" class="mt-2" />
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
                        <x-primary-button class="text-xl">
                            {{ __('Save changes') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>