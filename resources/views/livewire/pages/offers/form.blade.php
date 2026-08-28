<?php

use App\Models\Offer;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\Company;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Enums\Currency;
use App\Enums\OfferStatus;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component
{
    public ?Offer $offer = null;
    public string $pageTitle = '';

    public string $company_id = '';
    public string $contact_id = '';
    public string $deal_id = '';
    public string $offer_number = '';
    public string $title = '';
    public string $offer_issued = '';
    public string $offer_valid = '';
    public string $currency = '';
    public string $subtotal = '';
    public string $tax_rate = '';
    public string $tax_amount = '';
    public string $total = '';
    public string $status = '';

    // offer items
    public array $items = [];

    public function with(): array
    {
        $taxRate = (float) ($this->tax_rate ?: 0);
        $itemsTotal = collect($this->items)->sum(
            fn ($item) => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)
        );

        return [
            'deals' => Deal::latest()->get(),
            'companies' => Company::latest()->get(),
            'availableContacts' => $this->company_id
                ? Contact::where('company_id', $this->company_id)->get()
                : collect(),
            'currencies' => Currency::cases(),
            'statuses' => OfferStatus::cases(),
            'itemsSubtotal' => $itemsTotal,
            'itemsTotalWithTax' => $itemsTotal + ($itemsTotal * ($taxRate / 100)),
        ];
    }

    public function mount(?Offer $offer = null): void
    {
        $this->offer = $offer;
        $this->pageTitle = __('Add New Offer');

        if ($offer) {
            $this->pageTitle = __('Edit Offer') .' [ '. $offer->offer_number .' ]';
            $this->company_id = $offer->company_id ?? '';
            $this->contact_id = $offer->contact_id ?? '';
            $this->deal_id = $offer->deal_id ?? '';
            $this->offer_number = $offer->offer_number;
            $this->title = $offer->title;
            $this->offer_issued = $offer->offer_issued?->format('Y-m-d') ?? '';
            $this->offer_valid = $offer->offer_valid?->format('Y-m-d') ?? '';
            $this->subtotal = $offer->subtotal ?? '';
            $this->tax_rate = $offer->tax_rate ?? '';
            $this->tax_amount = $offer->tax_amount ?? '';
            $this->total = $offer->total ?? '';
            $this->currency = $offer->currency->value;
            $this->status = $offer->status->value;

            $this->items = $offer->offerItems->map(fn ($item) => [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ])->toArray();
        } else {
            $this->offer_number = 'INV-' . now()->year . '-' . str_pad(Offer::count() + 1, 3, '0', STR_PAD_LEFT);
            $this->currency = Currency::cases()[0]->value;
            $this->status = OfferStatus::cases()[0]->value;

            $this->items = [];

            if (request()->filled('deal_id')) {
                $this->deal_id = request()->query('deal_id');
                $this->updatedDealId($this->deal_id);
            }
        }
    } 


    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'offer_number' => ['required', 'string', 'max:255', Rule::unique('offers', 'offer_number')->ignore($this->offer)],
            'deal_id' => ['nullable', 'exists:deals,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'offer_issued' => ['nullable', 'date'],
            'offer_valid' => ['nullable', 'date'],
            'tax_rate' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', Rule::enum(Currency::class)],
            'status' => ['required', Rule::enum(OfferStatus::class)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['company_id'] = $validated['company_id'] ?: null;
        $validated['contact_id'] = $validated['contact_id'] ?: null;
        $validated['deal_id'] = $validated['deal_id'] ?: null;
        $validated['offer_valid'] = $validated['offer_valid'] ?: null;
        $validated['subtotal'] = 0;
        $validated['tax_amount'] = 0;
        $validated['total'] = 0;


        if ($this->offer) {
            $this->offer->update($validated);
            $this->offer->offerItems()->delete();
        } else {
            $validated['user_id'] = auth()->id();
            $this->offer = Offer::create($validated);
        }

        foreach ($this->items as $item) {
            $this->offer->offerItems()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $this->offer->calculateTotals();

        $this->redirect(route('offers.index'), navigate: true);
    }

    public function updatedDealId($value): void
    {
        if ($value) {
            $deal = Deal::find($value);
            $this->company_id = $deal->company_id ?? '';
            $this->contact_id = $deal->contact_id ?? '';
        }
    }

    public function updatedCompanyId(): void
    {
        $this->contact_id = '';
    }

    public function addItem(): void
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'unit_price' => 0];
    }

    public function removeItem($index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

};
?>


<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $pageTitle }}</h2>

            <div class="flex gap-3">
                @if ($offer)
                    <a href="{{ route('offers.pdf', $offer) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        {{ __('Create PDF') }}
                    </a>
                @endif
                <a href="{{ route('offers.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form wire:submit="save" class="max-w-xl">
                    <!-- offer number -->
                    <div>
                        <x-input-label for="offer_number" :value="__('Offer number')" />
                        <x-text-input wire:model="offer_number" id="offer_number" class="bg-gray-100 block mt-1 w-full" type="text" readonly />
                        <x-input-error :messages="$errors->get('offer_number')" class="mt-2" />
                    </div>

                    <!-- Title -->
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" name="title" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- offer_issued -->
                    <div class="mt-4">
                        <x-input-label for="offer_issued" :value="__('Offer issued')" />
                        <x-text-input wire:model="offer_issued" id="offer_issued" class="block mt-1 w-full" type="date" name="offer_issued" />
                        <x-input-error :messages="$errors->get('offer_issued')" class="mt-2" />
                    </div>

                    <!-- expected_close_date -->
                    <div class="mt-4">
                        <x-input-label for="offer_valid" :value="__('Offer valid')" />
                        <x-text-input wire:model="offer_valid" id="offer_valid" class="block mt-1 w-full" type="date" name="offer_valid" />
                        <x-input-error :messages="$errors->get('offer_valid')" class="mt-2" />
                    </div>

                    <!-- Deal -->
                    <div class="mt-4">
                        <x-input-label for="deal" :value="__('Deal')" />
                        <select wire:model.live="deal_id" id="deal" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">---</option>
                            @foreach ($deals as $deal)
                            <option value="{{ $deal->id }}">{{ $deal->title }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('deal_id')" class="mt-2" />
                    </div>

                    <!-- Company -->
                    <div class="mt-4">
                        <x-input-label for="company" :value="__('Company')" />
                        <select wire:model.live="company_id" id="company" @disabled($deal_id) class="block mt-1 w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
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
                        <select wire:model="contact_id" id="contact" @disabled($deal_id) class="block mt-1 w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100">
                            <option value="">---</option>
                            @foreach ($availableContacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->first_name }} {{ $contact->last_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                    </div>

                    <!-- currency -->
                    <div class="mt-4">
                        <x-input-label for="currency" :value="__('Currency')" />
                        <select wire:model.live="currency" id="currency" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($currencies as $case)
                                <option value="{{ $case->value }}">{{ $case->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                    </div>

                    <!-- tax_rate -->
                    <div class="mt-4">
                        <x-input-label for="tax_rate" :value="__('Tax rate')" />
                        <x-text-input wire:model="tax_rate" id="tax_rate" class="block mt-1 w-full" type="text" name="tax_rate" />
                        <x-input-error :messages="$errors->get('tax_rate')" class="mt-2" />
                    </div>

                    <div class="mt-6 py-3 px-4 bg-gray-100 rounded-lg">
                        <x-input-label :value="__('Items')" />

                        @foreach ($items as $index => $item)
                            <div class="flex gap-2 items-center mt-2" wire:key="item-{{ $index }}">
                                <input type="text" wire:model="items.{{ $index }}.description" placeholder="{{ __('Description') }}" class="flex-1 rounded-md border-gray-300 shadow-sm">
                                <input type="number" step="1" wire:model.live.debounce.300ms="items.{{ $index }}.quantity" class="w-20 rounded-md border-gray-300 shadow-sm">
                                <input type="number" step="0.01" wire:model.live.debounce.300ms="items.{{ $index }}.unit_price" class="w-28 rounded-md border-gray-300 shadow-sm">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 text-sm px-2">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        @endforeach

                        <button type="button" wire:click="addItem" class="mt-3 text-sm text-indigo-600">
                            + {{ __('Add item') }}
                        </button>
                    </div>

                    <!-- Total -->
                    <div class="mt-4">
                        <x-input-label :value="__('Total (with tax)')" />
                        <div class="mt-1 p-2 bg-gray-100 rounded-md font-semibold">
                            {{ number_format($itemsTotalWithTax, 2) }} {{ $currency }}
                        </div>
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