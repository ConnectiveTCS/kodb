<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 ">
        @include('partials.forms-speaker')

    </div>
</x-app-layout>
