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

Volt::route('contacts', 'pages.contacts.index')
    ->middleware(['auth'])
    ->name('contacts.index');

Volt::route('contacts/create', 'pages.contacts.form')
    ->middleware(['auth'])
    ->name('contacts.create');

Volt::route('contacts/{contact}/edit', 'pages.contacts.form')
    ->middleware(['auth'])
    ->name('contacts.edit');

require __DIR__.'/auth.php';
