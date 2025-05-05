<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Update Your Speaker Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Update Your Speaker Profile') }}
                </h2>
            </div>
        </header>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form action="{{ route('speakers.update-with-token', $token) }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center justify-center mt-4">
                            @csrf
                            <h1 class="text-2xl font-bold text-gray-800 mb-4">Update Your Profile</h1>
                            <p class="text-gray-600 mb-4">Please update your speaker information below.</p>
                            <div class="grid grid-cols-4 gap-2 w-3/4 mx-auto border border-gray-300 rounded-md p-4 shadow-md mt-4 bg-white">
                                <img src="{{ $speaker->photo ? asset('images/speakers/' . $speaker->photo) : 'https://robohash.org/' . $speaker->first_name }}" alt="{{ $speaker->first_name }}" id="profile_image_preview" class="w-32 h-32 rounded-full mb-4 col-span-4 object-cover object-top">

                                <div class="flex flex-col mb-4 col-span-4">
                                    <label for="photo" class="text-sm font-medium text-gray-700">Profile Image</label>
                                    <input type="file" id="photo" name="photo" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col mb-4 col-span-2">
                                    <label for="first_name" class="text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ $speaker->first_name }}" class="border border-gray-300 rounded-md p-2" required>
                                </div>
                                <div class="flex flex-col mb-4 col-span-2">
                                    <label for="last_name" class="text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ $speaker->last_name }}" class="border border-gray-300 rounded-md p-2" required>
                                </div>
                                <div class="flex flex-col mb-4 col-span-2">
                                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $speaker->email }}" class="border border-gray-300 rounded-md p-2" required>
                                </div>
                                <div class="flex flex-col mb-4 col-span-2">
                                    <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ $speaker->phone }}" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col mb-4 col-span-2">
                                    <label for="company" class="text-sm font-medium text-gray-700">Company</label>
                                    <input type="text" id="company" name="company" value="{{ $speaker->company }}" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col mb-4">
                                    <label for="job_title" class="text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" id="job_title" name="job_title" value="{{ $speaker->job_title }}" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col mb-4">
                                    <label for="industry" class="text-sm font-medium text-gray-700">Industry</label>
                                    <input type="text" id="industry" name="industry" value="{{ $speaker->industry }}" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col mb-4 col-span-4">
                                    <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
                                    <textarea id="bio" name="bio" rows="4" class="border border-gray-300 rounded-md p-2">{{ $speaker->bio }}</textarea>
                                    <button type="button" id="generateBioBtn" class="mt-2 bg-green-500 hover:bg-green-400 text-white py-1 px-2 rounded-md shadow-md">Generate with AI</button>
                                </div>
                                
                                @if($speaker->cv_resume)
                                <div class="flex flex-col mb-4 col-span-4">
                                    <label class="text-sm font-medium text-gray-700">Current CV</label>
                                    <div class="flex items-center">
                                        <a href="{{ asset('uploads/cv/' . $speaker->cv_resume) }}" target="_blank" class="text-blue-500 hover:text-blue-700">View Current CV</a>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex flex-col mb-4 col-span-4">
                                    <label for="cv_resume" class="text-sm font-medium text-gray-700">Upload New CV</label>
                                    <input type="file" id="cv_resume" name="cv_resume" class="border border-gray-300 rounded-md p-2">
                                </div>
                                
                                <button type="submit" class="col-span-2 bg-blue-500 hover:bg-blue-400 text-white py-2 px-4 rounded-md shadow-md">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bio Generation Modal -->
    <div id="bioModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center border-b p-4 sticky top-0 bg-white rounded-t-lg z-10">
                <h3 class="text-lg font-semibold">Generate Speaker Bio with AI</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-grow">
                <form method="POST" action="/api/generate-bio" id="bioForm" class="space-y-4">
                    @csrf
                    <div class="flex flex-col">
                        <label for="full_name" class="text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="border border-gray-300 rounded-md p-2" value="{{ $speaker->first_name }} {{ $speaker->last_name }}">
                    </div>
                    <div class="flex flex-col">
                        <label for="job_title" class="text-sm font-medium text-gray-700">Job Title</label>
                        <input type="text" id="job_title_bio" name="job_title" class="border border-gray-300 rounded-md p-2" value="{{ $speaker->job_title }}">
                    </div>
                    <div class="flex flex-col">
                        <label for="workplace" class="text-sm font-medium text-gray-700">Workplace</label>
                        <input type="text" id="workplace" name="workplace" class="border border-gray-300 rounded-md p-2" value="{{ $speaker->company }}">
                    </div>
                    <div class="flex flex-col">
                        <label for="expertise" class="text-sm font-medium text-gray-700">Area of Expertise</label>
                        <input type="text" id="expertise" name="expertise" class="border border-gray-300 rounded-md p-2" value="{{ $speaker->industry }}">
                    </div>
                    <div class="flex flex-col">
                        <label for="experience_years" class="text-sm font-medium text-gray-700">Years of Experience</label>
                        <input type="number" id="experience_years" name="experience_years" class="border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex flex-col">
                        <label for="topics" class="text-sm font-medium text-gray-700">Topics You're Passionate About</label>
                        <textarea id="topics" name="topics" class="border border-gray-300 rounded-md p-2" rows="2"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label for="events" class="text-sm font-medium text-gray-700">Notable Speaking Engagements (optional)</label>
                        <textarea id="events" name="events" class="border border-gray-300 rounded-md p-2" rows="2"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label for="awards" class="text-sm font-medium text-gray-700">Awards or Certifications (optional)</label>
                        <textarea id="awards" name="awards" class="border border-gray-300 rounded-md p-2" rows="2"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label for="motivation" class="text-sm font-medium text-gray-700">What Motivates You to Speak?</label>
                        <textarea id="motivation" name="motivation" class="border border-gray-300 rounded-md p-2" rows="2"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label for="fun_fact" class="text-sm font-medium text-gray-700">Interesting Fact About You (optional)</label>
                        <textarea id="fun_fact" name="fun_fact" class="border border-gray-300 rounded-md p-2" rows="2"></textarea>
                    </div>
                    <div class="flex justify-between pt-4">
                        <button type="button" id="cancelBio" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md">Cancel</button>
                        <button type="submit" id="generateBioSubmit" class="bg-blue-600 text-white px-4 py-2 rounded">Generate Bio</button>
                    </div>
                </form>

                <div id="bioOutput" class="mt-6 p-4 border rounded bg-gray-50 hidden">
                    <div class="flex justify-between mb-2">
                        <h4 class="font-medium">Generated Bio</h4>
                        <button id="useBio" class="text-blue-600 hover:text-blue-800">Use This Bio</button>
                    </div>
                    <div id="bioContent" class="text-gray-700 max-h-48 overflow-y-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile_image_preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Modal functionality
        const modal = document.getElementById('bioModal');
        const modalDialog = modal.querySelector('.bg-white');
        const generateBioBtn = document.getElementById('generateBioBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBio = document.getElementById('cancelBio');
        const bioForm = document.getElementById('bioForm');
        const bioOutput = document.getElementById('bioOutput');
        const useBio = document.getElementById('useBio');

        generateBioBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scrolling
        });

        cancelBio.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scrolling
        });
        
        // Close modal when clicking outside of it
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        bioForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            document.getElementById('generateBioSubmit').textContent = 'Generating...';
            document.getElementById('generateBioSubmit').disabled = true;
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Log the target endpoint for debugging
            console.log('Sending request to: /api/generate-bio');

            try {
                // First check if the API is accessible
                try {
                    const pingResponse = await fetch('/api/ping', { 
                        method: 'HEAD',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (!pingResponse.ok) {
                        throw new Error(`API connection check failed with status: ${pingResponse.status}`);
                    }
                } catch (pingError) {
                    console.error('API connectivity test failed:', pingError);
                    // Continue anyway, but log the error
                }
                
                const response = await fetch('/api/generate-bio', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                    // Add timeout to prevent hanging requests
                    signal: AbortSignal.timeout(60000) // 60 second timeout
                });

                const result = await response.json();
                bioOutput.classList.remove('hidden');
                
                if (response.ok) {
                    document.getElementById('bioContent').textContent = result.bio;
                } else {
                    console.error('Server returned an error:', result);
                    document.getElementById('bioContent').textContent = 
                        'Error: ' + (result.error || 'Failed to generate bio. Please try again.');
                }
            } catch (error) {
                console.error('Error generating bio:', error);
                bioOutput.classList.remove('hidden');
                
                let errorMessage = 'Network error while generating the bio. ';
                
                // Provide more specific error information when possible
                if (error.name === 'AbortError') {
                    errorMessage += 'The request timed out. ';
                } else if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                    errorMessage += 'Could not connect to the API server. ';
                } else if (error.message) {
                    errorMessage += `Error details: ${error.message}. `;
                }
                
                errorMessage += 'Please check your connection and try again.';
                document.getElementById('bioContent').textContent = errorMessage;
            } finally {
                document.getElementById('generateBioSubmit').textContent = 'Generate Bio';
                document.getElementById('generateBioSubmit').disabled = false;
            }
        });

        useBio.addEventListener('click', function() {
            const generatedBio = document.getElementById('bioContent').textContent;
            document.getElementById('bio').value = generatedBio;
            modal.classList.add('hidden');
        });
    </script>
</body>
</html>
