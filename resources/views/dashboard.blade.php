<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-row items-center justify-between border-b pb-3 mb-3">
                <h3 class="text-xl font-bold text-gray-800">Speakers</h3>
                <h4 class="bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm font-medium">Total: {{ $count ?? '85' }}</h4>
            </div>
            <p class="mt-2 text-gray-600 font-medium text-sm uppercase tracking-wide">Recently Added</p>
            {{-- Show latest speakers --}}
            <ul class="mt-4 space-y-3">
                @foreach ($latest as $l)
                    <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition duration-150 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center">
                            {{-- add placeholder image url --}}
                            <img src="{{ $l->photo ?? "https://robohash.org/$l->first_name" }}" alt="{{ $l->first_name ?? '' }}"
                                class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-blue-100">
                            <div class="flex flex-col">
                                <span class="text-gray-800 font-semibold">{{ $l->first_name ?? '' }} {{ $l->last_name ?? '' }}</span>
                                <span class="text-gray-500 text-sm"><a href="mailto:{{ $l->email ?? '' }}" class="hover:text-blue-600 transition">{{ $l->email ?? '' }}</a></span>
                                <span class="text-gray-500 text-sm"><a href="tel:{{ $l->phone ?? '' }}" class="hover:text-blue-600 transition">{{ $l->phone ?? '' }}</a></span>
                            </div>
                        </div>
                        <div>
                            {{-- <a href="{{ route('speakers.show', $l->id) }}" --}}
                            <a href="{{ route('speakers.show', $l->id) }}"
                                class="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg font-medium text-sm transition-colors duration-150">View</a>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 text-center">
                <a href=""
                    class="text-blue-500 hover:text-blue-700 font-semibold">View all speakers</a>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold">Partners</h3>
            <p class="mt-2 text-gray-600">This is the content of card 2.</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold">Volunteers</h3>
            <p class="mt-2 text-gray-600">This is the content of card 3.</p>
        </div>

    </div>
</x-app-layout>
