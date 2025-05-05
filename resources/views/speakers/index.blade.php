<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Speakers') }}
        </h2>
    </x-slot>
    
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
            @if(session('import_results'))
                <button id="view-import-details" type="button" class="ml-2 text-sm text-green-800 underline">View import details</button>
            @endif
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>
    @endif
    
    <div class="grid gap-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Action Bar -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('speakers.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Speaker
                    </a>
                    <button id="open-import-modal" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import
                    </button>
                    <a href="{{ route('speakers.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </a>
                    <button id="delete-selected" type="button" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out hidden">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                    </button>
                    <button type="button" id="open-filter-modal" class="ml-auto inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414v6.586a1 1 0 01-1.414.707l-2-2A1 1 0 0110 17.586V13.7l-6.293-6.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Columns
                    </button>
                </div>
            </div>
            <!-- Search/Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <form method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-12 md:gap-4">
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" placeholder="Search name, email, phone"
                                value="{{ old('search', $search ?? '') }}"
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-3 py-2 sm:text-sm border-gray-300 rounded-md" />
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <select id="company" name="company" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full py-2 px-3 sm:text-sm border-gray-300 rounded-md">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company }}" {{ (isset($currentCompany) && $currentCompany == $company) ? 'selected' : '' }}>
                                    {{ $company }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                        <select id="industry" name="industry" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full py-2 px-3 sm:text-sm border-gray-300 rounded-md">
                            <option value="">All Industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" {{ (isset($currentIndustry) && $currentIndustry == $industry) ? 'selected' : '' }}>
                                    {{ $industry }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414v6.586a1 1 0 01-.414.707l-4 2a1 1 0 01-1.447-.894V13.7l-6.293-6.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('speakers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Clear
                        </a>
                    </div>
                    {{-- Keep column selection in filter --}}
                    @foreach(request('columns', []) as $col)
                        <input type="hidden" name="columns[]" value="{{ $col }}">
                    @endforeach
                </form>
            </div>
            @php
            $columns = [
                'id' => '#',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'company' => 'Company',
                'industry' => 'Industry',
            ];
            $currentSort = request('sort', 'id');
            $currentDirection = request('direction', 'asc');
            $visibleColumns = $visibleColumns ?? array_keys($columns);
            @endphp
            <!-- Table Section -->
            <div class="overflow-x-auto">
                <!-- Hidden form for batch delete -->
                <form id="batch-delete-form" action="{{ route('speakers.batch-delete') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="selected_ids" id="selected-ids-input">
                </form>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="pl-6 py-3 text-left">
                                <div class="flex items-center">
                                    <input id="select-all" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </div>
                            </th>
                            @foreach ($columns as $col => $label)
                                @if(in_array($col, $visibleColumns))
                                    @php
                                        $isCurrent = $currentSort === $col;
                                        $direction = $isCurrent && $currentDirection === 'asc' ? 'desc' : 'asc';
                                        $arrow = $isCurrent ? ($currentDirection === 'asc' ? '↑' : '↓') : '';
                                    @endphp
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => $col, 'direction' => $direction]) }}" 
                                           class="group inline-flex items-center {{ $isCurrent ? 'text-indigo-600' : '' }}">
                                            {{ $label }}
                                            @if($isCurrent)
                                                <span class="ml-2">
                                                    <svg class="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        @if($currentDirection === 'asc')
                                                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        @else
                                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        @endif
                                                    </svg>
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                                @endif
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($speakers as $speaker)
                        <tr class="hover:bg-gray-50">
                            <td class="pl-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox" class="speaker-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="{{ $speaker->id }}">
                                </div>
                            </td>
                            @foreach ($columns as $col => $label)
                                @if(in_array($col, $visibleColumns))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $speaker->$col }}</td>
                                @endif
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('speakers.show', $speaker->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <form action="{{ route('speakers.destroy', $speaker->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Are you sure you want to delete this speaker?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Empty State - Show when no speakers exist -->
    @if(count($speakers) === 0)
    <div class="py-12 flex flex-col items-center justify-center text-gray-500">
        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="mt-2 text-xl">No speakers found</p>
        <p class="mt-1">Add speakers or adjust your filters to see results.</p>
    </div>
    @endif
    <!-- Modal -->
    <div id="filter-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start pb-3 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Select Columns to Display
                        </h3>
                        <button type="button" id="close-filter-modal" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="GET" class="mt-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            @foreach($columns as $col => $label)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="columns[]" value="{{ $col }}" {{ in_array($col, $visibleColumns) ? 'checked' : '' }} 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="ml-2 text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Apply Selection
                            </button>
                            <button type="button" id="cancel-filter-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Import Modal -->
    <div id="import-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start pb-3 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Import Speakers from CSV
                        </h3>
                        <button type="button" id="close-import-modal" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="import-form" action="{{ route('speakers.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-4">Upload a CSV file with speaker information. The file should include columns for first_name, email, and optionally phone, company, job_title, bio, and industry.</p>
                            
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                            <input type="file" name="file" id="file" accept=".csv,.txt" required
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-2 text-xs text-gray-500">Accepted formats: .csv, .txt</p>
                            
                            <div id="file-error" class="mt-2 text-sm text-red-600 hidden">Please select a valid CSV file</div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" id="import-submit-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span>Upload & Import</span>
                            </button>
                            <div id="import-loading" class="hidden sm:ml-3 sm:w-auto px-4 py-2">
                                <div class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Importing...
                                </div>
                            </div>
                            <button type="button" id="cancel-import-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Import Results Modal -->
    @if(session('import_results'))
    <div id="import-results-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start pb-3 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Import Results
                        </h3>
                        <button type="button" id="close-results-btn" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <div class="mb-4">
                            <div class="flex space-x-4">
                                <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                    <p class="text-sm font-semibold">Successfully Imported</p>
                                    <p class="text-xl font-bold">{{ session('import_results')['success_count'] }}</p>
                                </div>
                                <div class="px-4 py-2 bg-red-100 text-red-800 rounded-lg">
                                    <p class="text-sm font-semibold">Skipped</p>
                                    <p class="text-xl font-bold">{{ session('import_results')['skip_count'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Display detected headers -->
                        @if(isset(session('import_results')['original_headers']))
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">CSV Headers Detected</h4>
                                <div class="bg-gray-50 p-3 rounded-lg text-sm">
                                    <p><strong>Headers found:</strong> {{ implode(', ', session('import_results')['original_headers']) }}</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Required columns: "first_name" (or "First Name", "Name", etc.) and "email" (or "Email Address", etc.)
                                    </p>
                                </div>
                            </div>
                        @endif
                        
                        @if(count(session('import_results')['skip_reasons']) > 0)
                            <h4 class="font-medium text-gray-900 mb-2">Skipped Entries</h4>
                            <div class="bg-gray-50 rounded-lg max-h-64 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(session('import_results')['skip_reasons'] as $skip)
                                            <tr>
                                                <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $skip['row'] }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap text-sm text-red-600">{{ $skip['reason'] }}</td>
                                                <td class="px-6 py-2 text-sm text-gray-500">{{ $skip['data'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" id="close-results-modal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterModal = document.getElementById('filter-modal');
            const openFilterBtn = document.getElementById('open-filter-modal');
            const closeFilterBtn = document.getElementById('close-filter-modal');
            const cancelFilterBtn = document.getElementById('cancel-filter-modal');
            
            const importModal = document.getElementById('import-modal');
            const openImportBtn = document.getElementById('open-import-modal');
            const closeImportBtn = document.getElementById('close-import-modal');
            const cancelImportBtn = document.getElementById('cancel-import-modal');
            
            function showModal(modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            
            function hideModal(modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            openFilterBtn.addEventListener('click', () => showModal(filterModal));
            closeFilterBtn.addEventListener('click', () => hideModal(filterModal));
            cancelFilterBtn.addEventListener('click', () => hideModal(filterModal));
            
            filterModal.addEventListener('click', function(event) {
                if (event.target === filterModal) {
                    hideModal(filterModal);
                }
            });
            
            openImportBtn.addEventListener('click', () => showModal(importModal));
            closeImportBtn.addEventListener('click', () => hideModal(importModal));
            cancelImportBtn.addEventListener('click', () => hideModal(importModal));
            
            importModal.addEventListener('click', function(event) {
                if (event.target === importModal) {
                    hideModal(importModal);
                }
            });
            
            const selectAllCheckbox = document.getElementById('select-all');
            const speakerCheckboxes = document.querySelectorAll('.speaker-checkbox');
            const deleteSelectedBtn = document.getElementById('delete-selected');
            const batchDeleteForm = document.getElementById('batch-delete-form');
            const selectedIdsInput = document.getElementById('selected-ids-input');
            
            function updateDeleteButtonVisibility() {
                const checkedCount = document.querySelectorAll('.speaker-checkbox:checked').length;
                if (checkedCount > 0) {
                    deleteSelectedBtn.classList.remove('hidden');
                    deleteSelectedBtn.textContent = `Delete Selected (${checkedCount})`;
                } else {
                    deleteSelectedBtn.classList.add('hidden');
                }
            }
            
            selectAllCheckbox.addEventListener('change', function() {
                speakerCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateDeleteButtonVisibility();
            });
            
            speakerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateDeleteButtonVisibility();
                    
                    const allChecked = Array.from(speakerCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(speakerCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                });
            });
            
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.speaker-checkbox:checked'))
                    .map(checkbox => checkbox.value);
                
                if (selectedIds.length === 0) return;
                
                if (confirm(`Are you sure you want to delete ${selectedIds.length} selected speaker(s)?`)) {
                    selectedIdsInput.value = JSON.stringify(selectedIds);
                    batchDeleteForm.submit();
                }
            });
            
            const importForm = document.getElementById('import-form');
            const importSubmitBtn = document.getElementById('import-submit-btn');
            const importLoading = document.getElementById('import-loading');
            const fileInput = document.getElementById('file');
            const fileError = document.getElementById('file-error');
            
            importForm.addEventListener('submit', function(e) {
                if (!fileInput.files || fileInput.files.length === 0) {
                    e.preventDefault();
                    fileError.classList.remove('hidden');
                    return false;
                }
                
                importSubmitBtn.classList.add('hidden');
                importLoading.classList.remove('hidden');
                
                return true;
            });
            
            fileInput.addEventListener('change', function() {
                if (fileInput.files && fileInput.files.length > 0) {
                    fileError.classList.add('hidden');
                }
            });
            
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.classList.add('opacity-0', 'transition', 'duration-500');
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 5000);
                
                const closeBtn = message.querySelector('svg[role="button"]');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        message.remove();
                    });
                }
            });
            
            const viewImportDetailsBtn = document.getElementById('view-import-details');
            const importResultsModal = document.getElementById('import-results-modal');
            const closeResultsBtn = document.getElementById('close-results-btn');
            const closeResultsModalBtn = document.getElementById('close-results-modal');
            
            if (viewImportDetailsBtn) {
                viewImportDetailsBtn.addEventListener('click', () => showModal(importResultsModal));
            }
            
            if (closeResultsBtn) {
                closeResultsBtn.addEventListener('click', () => hideModal(importResultsModal));
            }
            
            if (closeResultsModalBtn) {
                closeResultsModalBtn.addEventListener('click', () => hideModal(importResultsModal));
            }
            
            if (importResultsModal) {
                importResultsModal.addEventListener('click', function(event) {
                    if (event.target === importResultsModal) {
                        hideModal(importResultsModal);
                    }
                });
            }
        });
    </script>
</x-app-layout>
