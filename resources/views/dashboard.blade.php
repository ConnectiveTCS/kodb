<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-row items-center justify-between">
                <h3 class="text-lg font-semibold">Speakers</h3>
                <h4>Total: {{ $count ?? '85' }}</h4>
            </div>
            <p class="mt-2 text-gray-600">Recently Added</p>
            {{-- Show latest speakers --}}
            <ul class="mt-2">
                @foreach ($latest as $l)
                    <li class="flex items-center justify-between p-2 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-center">
                            {{-- add placeholder image url --}}
                            <img src="https://robohash.org/{{ $l->first_name }}" alt="{{ $l->first_name ?? '' }}"
                                class="w-10 h-10 rounded-full mr-2">
                            <div class="flex flex-col">
                                <span class="text-gray-800">{{ $l->first_name ?? '' }} {{ $l->last_name ?? '' }}</span>
                                <span class="text-gray-500 text-sm"><a href="mailto:{{ $l->email ?? '' }}">{{ $l->email ?? '' }}</a></span>
                                <span class="text-gray-500 text-sm"><a href="tel:{{ $l->phone ?? '' }}">{{ $l->phone ?? '' }}</a></span>
                            </div>
                        </div>
                        <div>
                            {{-- <a href="{{ route('speakers.show', $l->id) }}" --}}
                            <a href=""
                                class="text-blue-500 hover:text-blue-700 font-semibold">View</a>

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
