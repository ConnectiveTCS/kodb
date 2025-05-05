<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Speaker Profile') }}
        </h2>
    </x-slot>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
        {{-- show the speaker --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col md:flex-row">
                <!-- Speaker Image -->
                <div class="w-full md:w-1/3 flex mb-6 md:mb-0 flex-col items-center ">
                    <div class="relative w-48 h-48">
                        <!-- Skeleton loader animation -->
                        <div class="skeleton-loader absolute inset-0 rounded-full bg-gray-200 animate-pulse"></div>
                        
                        @if ($speaker->photo)
                            <img src="{{ $speaker->photo }}" alt="{{ $speaker->first_name }}"
                                class="rounded-full w-48 h-48 object-cover shadow-lg object-top opacity-0 transition-opacity duration-300"
                                onload="this.classList.remove('opacity-0')">
                        @else
                            <img src="https://robohash.org/{{ $speaker->first_name }}" alt="{{ $speaker->first_name }}"
                                class="rounded-full w-48 h-48 object-cover shadow-lg object-top opacity-0 transition-opacity duration-300"
                                onload="this.classList.remove('opacity-0')">
                        @endif
                    </div>
                    <div class="mb-6">
                        @if ($speaker->cv_resume)
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">CV/Resume</h3>
                            <div class="text-gray-600">
                                {{-- PDF preview card instead of direct embedding --}}
                                <div class="border rounded-lg p-6 bg-gray-50 mb-2">
                                    <div class="flex items-center justify-center flex-col">
                                        <div class="mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-medium mb-2">CV/Resume Document</h4>
                                        <p class="text-gray-500 mb-4 text-center">The document is available for viewing or download</p>
                                        
                                        <div class="flex space-x-4">
                                            <a href="{{ $speaker->cv_resume }}" target="_blank" rel="noopener noreferrer"
                                               class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View PDF
                                            </a>
                                            <a href="{{ $speaker->cv_resume }}" download 
                                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Speaker Details -->
                <div class="w-full md:w-2/3 md:pl-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $speaker->first_name }} 
                        <span class="text-gray-500">{{ $speaker->last_name }}</span>
                        @if ($speaker->job_title && $speaker->company)
                          | 
                        <span class="text-gray-500">{{ $speaker->job_title }}</span> At 
                        <span class="text-gray-500">{{ $speaker->company }}</span>
                        @elseif ($speaker->company)
                         | 
                        <span class="text-gray-500">{{ $speaker->company }}</span>
                        @elseif ($speaker->company)
                        @endif
                    </h1>
                    <p class="text-gray-600 mb-4">{{ $speaker->industry ?? '' }}</p>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">About</h3>
                        @if ($speaker->bio)
                        <p class="text-gray-600">{{ $speaker->bio ?? 'No Bio'}}</p>
                        @else
                            <p class="text-gray-600">No Bio</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Contact Information</h3>
                        <div class="text-gray-600 flex gap-2 items-center">
                            <p class="">Email:</p> <a href="mailto:{{ $speaker->email }}">{{ $speaker->email }}</a>
                        </div>
                        @if ($speaker->phone)
                            <div class="text-gray-600 flex gap-2 items-center">
                                <p class="">Phone Number:</p> {{ $speaker->phone }}
                            </div>
                        @endif
                    </div>

                    
                </div>
            </div>

            <!-- Past Speaking Events -->
            @if (count($speaker->events ?? []) > 0)
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Past Speaking Engagements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($speaker->events as $event)
                            <div class="bg-gray-50 rounded p-4">
                                <h4 class="font-medium text-gray-800">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $event->date->format('M d, Y') }}</p>
                                @if ($event->location)
                                    <p class="text-sm text-gray-600">{{ $event->location }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                @if(Auth::user() && Auth::user()->isAdmin())
                    <a href="{{ route('speakers.edit', $speaker) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        Edit Profile
                    </a>
                @endif

                <a href="{{ route('speakers.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                    Back to Speakers
                </a>
            </div>
        </div>
    </div>

    <style>
        .skeleton-loader {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        img {
            position: relative;
            z-index: 10;
        }
        
        img.opacity-0 + .skeleton-loader {
            opacity: 1;
        }
        
        img:not(.opacity-0) + .skeleton-loader {
            opacity: 0;
        }
    </style>

    <script>
        // Handle image load events
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.opacity-0');
            images.forEach(img => {
                if (img.complete) {
                    img.classList.remove('opacity-0');
                }
                
                img.addEventListener('load', function() {
                    this.classList.remove('opacity-0');
                    const loader = this.parentNode.querySelector('.skeleton-loader');
                    if (loader) {
                        loader.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
