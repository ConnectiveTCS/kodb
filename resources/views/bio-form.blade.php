<form method="POST" action="{{ url('/generate-bio') }}">
    @csrf
    <!-- Your form fields here -->
    <button type="submit">Generate Bio</button>
</form>
