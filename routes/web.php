<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Models\Offer;
use Barryvdh\DomPDF\Facade\Pdf;

Route::view('/', 'welcome');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Companies

Volt::route('companies', 'pages.companies.index')
    ->middleware(['auth'])
    ->name('companies.index');

Volt::route('companies/create', 'pages.companies.form')
    ->middleware(['auth'])
    ->name('companies.create');

Volt::route('companies/{company}/edit', 'pages.companies.form')
    ->middleware(['auth'])
    ->name('companies.edit');

Volt::route('companies/kanban', 'pages.companies.kanban')
    ->middleware(['auth'])
    ->name('companies.kanban');


// Contacts

Volt::route('contacts', 'pages.contacts.index')
    ->middleware(['auth'])
    ->name('contacts.index');

Volt::route('contacts/create', 'pages.contacts.form')
    ->middleware(['auth'])
    ->name('contacts.create');

Volt::route('contacts/{contact}/edit', 'pages.contacts.form')
    ->middleware(['auth'])
    ->name('contacts.edit');

Volt::route('contacts/kanban', 'pages.contacts.kanban')
    ->middleware(['auth'])
    ->name('contacts.kanban');


// Deals

Volt::route('deals', 'pages.deals.index')
    ->middleware(['auth'])
    ->name('deals.index');

Volt::route('deals/create', 'pages.deals.form')
    ->middleware(['auth'])
    ->name('deals.create');

Volt::route('deals/{deal}/edit', 'pages.deals.form')
    ->middleware(['auth'])
    ->name('deals.edit');

Volt::route('deals/kanban', 'pages.deals.kanban')
    ->middleware(['auth'])
    ->name('deals.kanban');


// Offers

Volt::route('offers', 'pages.offers.index')
    ->middleware(['auth'])
    ->name('offers.index');

Volt::route('offers/create', 'pages.offers.form')
    ->middleware(['auth'])
    ->name('offers.create');

Volt::route('offers/{offer}/edit', 'pages.offers.form')
    ->middleware(['auth'])
    ->name('offers.edit');

Volt::route('offers/kanban', 'pages.offers.kanban')
    ->middleware(['auth'])
    ->name('offers.kanban');

Route::get('offers/{offer}/pdf', function (Offer $offer) {
    $offer->load(['offerItems', 'company', 'contact']);

    return Pdf::loadView('pdf.offer', ['offer' => $offer])
        ->setPaper('a4', 'portrait')
        ->download("offer-{$offer->offer_number}.pdf");
})->middleware(['auth'])->name('offers.pdf');

require __DIR__.'/auth.php';
