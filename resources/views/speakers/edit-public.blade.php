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
    </script>
</body>
</html>
