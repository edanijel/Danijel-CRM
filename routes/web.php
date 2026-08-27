<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

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



require __DIR__.'/auth.php';
