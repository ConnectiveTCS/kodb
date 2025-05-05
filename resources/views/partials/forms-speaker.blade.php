<form action="{{ route('speakers.store') }}" enctype="multipart/form-data" class="flex flex-col items-center justify-center mt-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Speaker Registration</h1>
    <p class="text-gray-600 mb-4">Please fill out the form below to register as a speaker.</p>
    <div class="grid grid-cols-4 gap-2 w-3/4 mx-auto border border-gray-300 rounded-md p-4 shadow-md mt-4 bg-white">
        <img src="{{ asset('1.jpg') }}" alt="" id="profile_image_preview" class="w-32 h-32 rounded-full mb-4 col-span-4 object-cover object-top">

        <div class="flex flex-col mb-4 col-span-4">
            <label for="photo" class="text-sm font-medium text-gray-700">Profile Image</label>
            <input type="file" id="photo" name="photo" class="border border-gray-300 rounded-md p-2">
        </div>
        <div class="flex flex-col mb-4 col-span-2">
            <label for="first_name" class="text-sm font-medium text-gray-700">First Name</label>
            <input type="text" id="first_name" name="first_name" class="border border-gray-300 rounded-md p-2" required>
        </div>
        <div class="flex flex-col mb-4 col-span-2">
            <label for="last_name" class="text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="border border-gray-300 rounded-md p-2" required>
        </div>
        <div class="flex flex-col mb-4 col-span-2">
            <label for="email" class="text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="border border-gray-300 rounded-md p-2" required>
        </div>
        <div class="flex flex-col mb-4 col-span-2">
            <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" id="phone" name="phone" class="border border-gray-300 rounded-md p-2">
        </div>
        <div class="flex flex-col mb-4 col-span-2">
            <label for="company" class="text-sm font-medium text-gray-700">Company</label>
            <input type="text" id="company" name="company" class="border border-gray-300 rounded-md p-2">
        </div>
        <div class="flex flex-col mb-4">
            <label for="job_title" class="text-sm font-medium text-gray-700">Position</label>
            <input type="text" id="job_title" name="job_title" class="border border-gray-300 rounded-md p-2">
        </div>
        <div class="flex flex-col mb-4">
            <label for="industry" class="text-sm font-medium text-gray-700">Industry</label>
            <input type="url" id="industry" name="industry" class="border border-gray-300 rounded-md p-2">
        </div>
        <div class="flex flex-col mb-4 col-span-4">
            <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
            <textarea id="bio" name="bio" rows="4" class="border border-gray-300 rounded-md p-2"></textarea>
        </div>
        <button type="submit" class=" col-span-1 bg-green-500 hover:bg-green-400 py-1 px-2 rounded-md shadow-md">Submit</button>
    </div>
    
</form>
{{-- show profile when user selects the photo --}}
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