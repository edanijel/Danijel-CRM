<?php

use App\Models\Company;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Enums\CompanyStatus;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component
{
    public ?Company $company = null;
    public string $title = '';

    public string $name = '';
    public string $oib = '';
    public string $email = '';
    public string $phone = '';
    public string $website = '';
    public string $address = '';
    public string $city = '';
    public string $postal_code = '';
    public string $status = '';

    public function with(): array
    {
        return [
            'statuses' => CompanyStatus::cases(),
        ];
    }

    public function mount(?Company $company = null): void
    {
        $this->company = $company;
        $this->title = $company ? __('Edit Company') : __('Add New Company');

        if ($company) {
            $this->name = $company->name;
            $this->oib = $company->oib ?? '';
            $this->email = $company->email ?? '';
            $this->phone = $company->phone ?? '';
            $this->website = $company->website ?? '';
            $this->address = $company->address ?? '';
            $this->city = $company->city ?? '';
            $this->postal_code = $company->postal_code ?? '';
            $this->status = $company->status->value;
        } else {
            $this->status = CompanyStatus::cases()[0]->value;
        }
    }


    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'oib' => ['nullable'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'website' => ['nullable'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'postal_code' => ['nullable'],
            'status' => ['required', Rule::enum(CompanyStatus::class)],
        ]);

        if ($this->company) {
            $this->company->update($validated);
        } else {
            Company::create($validated);
        }

        $this->redirect(route('companies.index'), navigate: true);
    }

};
?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>
            <a href="{{ route('companies.index') }}" wire:navigate class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form wire:submit="save" class="max-w-xl">
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- OIB -->
                    <div class="mt-4">
                        <x-input-label for="oib" :value="__('OIB')" />
                        <x-text-input wire:model="oib" id="oib" class="block mt-1 w-full" type="text" name="oib" />
                        <x-input-error :messages="$errors->get('oib')" class="mt-2" />
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


                    <!-- website -->
                    <div class="mt-4">
                        <x-input-label for="website" :value="__('Website')" />
                        <x-text-input wire:model="website" id="website" class="block mt-1 w-full" type="text" name="website" />
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>

                    <!-- address -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input wire:model="address" id="address" class="block mt-1 w-full" type="text" name="address" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- city -->
                    <div class="mt-4">
                        <x-input-label for="city" :value="__('City')" />
                        <x-text-input wire:model="city" id="city" class="block mt-1 w-full" type="text" name="city" />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <!-- postal_code -->
                    <div class="mt-4">
                        <x-input-label for="postal_code" :value="__('Postal Code')" />
                        <x-text-input wire:model="postal_code" id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" />
                        <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
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

                    <div class="mt-6">
                        <x-primary-button>
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>