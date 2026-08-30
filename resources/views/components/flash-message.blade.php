@if (session('success'))
    <div 
        class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4"
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
    >
        <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow">
            {{ session('success') }}
        </div>
    </div>
@endif