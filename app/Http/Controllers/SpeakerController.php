<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SpeakerUpdateInvitation;
use Carbon\Carbon;

class SpeakerController extends Controller
{
    //CRUD
    public function index(Request $request)
    {
        // Allowed columns for sorting
        $sortable = [
            'id', 'first_name', 'last_name', 'email', 'phone',
            'company', 'job_title', 'industry', 'bio'
        ];
        $sort = request('sort', 'id');
        $direction = request('direction', 'asc');

        // Validate sort column and direction
        if (!in_array($sort, $sortable)) {
            $sort = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Filtering
        $query = Speaker::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company')) {
            $query->where('company', $request->input('company'));
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->input('industry'));
        }

        // Get all speakers with sorting
        $speakers = $query->orderBy($sort, $direction)->get();

        $allColumns = [
            'id', 'first_name', 'last_name', 'email', 'phone', 'company', 'job_title', 'industry', 'bio'
        ];
        $visibleColumns = $request->input('columns', $allColumns);

        // Get unique companies and industries for dropdowns
        $companies = Speaker::select('company')->distinct()->whereNotNull('company')->pluck('company');
        $industries = Speaker::select('industry')->distinct()->whereNotNull('industry')->pluck('industry');

        return view('speakers.index', [
            'speakers' => $speakers,
            'visibleColumns' => $visibleColumns,
            'companies' => $companies,
            'industries' => $industries,
            'currentCompany' => $request->input('company', ''),
            'currentIndustry' => $request->input('industry', ''),
            'search' => $request->input('search', ''),
        ]);
    }
    public function create()
    {
        return view('speakers.create');
    }
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:speakers,email',
            'phone' => 'nullable',
            'company' => 'nullable',
            'job_title' => 'nullable',
            'bio' => 'nullable',
            'industry' => 'nullable',
            //cv
            'cv_resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        //handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/speakers'), $filename);
            $request->merge(['photo' => $filename]);
        }
        //handle cv upload
        if ($request->hasFile('cv_resume')) {
            $file = $request->file('cv_resume');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cv'), $filename);
            $request->merge(['cv_resume' => $filename]);
        }
        //get user id
        $user = Auth::user();
        //merge user id to request
        $request->merge(['user_id' => $user->id]);

        //create speaker
        Speaker::create($request->all());

        // dd($request->all());

        return redirect()->route('speakers.index')->with('success', 'Speaker created successfully.');
    }
    public function show($id)
    {
        //get speaker by id
        $speaker = Speaker::findOrFail($id);
        return view('speakers.show', compact('speaker'));
    }
    public function edit($id)
    {
        //get speaker by id
        $speaker = Speaker::findOrFail($id);
        return view('speakers.edit', compact('speaker'));
    }
    public function update(Request $request, $id)
    {
        //validate request
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:speakers,email,' . $id,
            'phone' => 'nullable',
            'company' => 'nullable',
            'job_title' => 'nullable',
            'bio' => 'nullable',
            'industry' => 'nullable',
            //cv
            'cv_resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        //handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/speakers'), $filename);
            $request->merge(['photo' => $filename]);
        }
        //handle cv upload
        if ($request->hasFile('cv_resume')) {
            $file = $request->file('cv_resume');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cv'), $filename);
            $request->merge(['cv_resume' => $filename]);
        }

        //update speaker
        Speaker::findOrFail($id)->update($request->all());

        return redirect()->route('speakers.index')->with('success', 'Speaker updated successfully.');
    }
    public function destroy($id)
    {
        //delete speaker
        Speaker::findOrFail($id)->delete();
        return redirect()->route('speakers.index')->with('success', 'Speaker deleted successfully.');
    }

    /**
     * Delete multiple speakers at once.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchDelete(Request $request)
    {
        $selectedIds = json_decode($request->input('selected_ids'));
        
        if (empty($selectedIds)) {
            return redirect()->route('speakers.index')
                ->with('error', 'No speakers selected for deletion.');
        }
        
        // Delete the selected speakers
        Speaker::whereIn('id', $selectedIds)->delete();
        
        $count = count($selectedIds);
        return redirect()->route('speakers.index')
            ->with('success', "{$count} speaker(s) deleted successfully.");
    }
    //import speakers via csv and

    // Helper function to properly format name with first letter uppercase
    private function formatName($name)
    {
        // Split name by spaces
        $parts = explode(' ', $name);
        $formattedParts = [];

        foreach ($parts as $part) {
            if (!empty($part)) {
                // Handle hyphenated names like "Al-Abri"
                if (strpos($part, '-') !== false) {
                    $hyphenParts = explode('-', $part);
                    $formattedHyphenParts = array_map(function ($p) {
                        return ucfirst(strtolower($p));
                    }, $hyphenParts);
                    $formattedParts[] = implode('-', $formattedHyphenParts);
                } else {
                    $formattedParts[] = ucfirst(strtolower($part));
                }
            }
        }

        // Join and return the formatted name
        return implode(' ', $formattedParts);
    }

    public function import(Request $request)
    {
        // Validate request
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        // Handle file upload - store in a more appropriate directory for CSV files
        $filePath = null;
        $successCount = 0;
        $skipCount = 0;
        $skipReasons = []; // Track reasons for skipping
        $originalHeaders = []; // Store the original headers from CSV

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('uploads/csv/' . $filename);
                
                // Ensure directory exists
                if (!file_exists(public_path('uploads/csv'))) {
                    mkdir(public_path('uploads/csv'), 0755, true);
                }
                
                $file->move(public_path('uploads/csv'), $filename);
            }

            // Import speakers
            if (!file_exists($filePath)) {
                return redirect()->route('speakers.index')->with('error', 'CSV file not found.');
            }

            // Read the CSV file as binary first to detect and remove BOM if present
            $content = file_get_contents($filePath);
            // Remove BOM character if present
            $bom = pack('H*', 'EFBBBF');
            $content = preg_replace("/^$bom/", '', $content);
            // Write back clean content
            file_put_contents($filePath, $content);

            $handle = fopen($filePath, 'r');
            if (!$handle) {
                return redirect()->route('speakers.index')->with('error', 'Could not open file.');
            }
            
            $header = fgetcsv($handle, 1000, ',');
            if (!$header) {
                fclose($handle);
                return redirect()->route('speakers.index')->with('error', 'Empty or invalid CSV file.');
            }

            // Save original headers for debugging
            $originalHeaders = $header;

            // Clean and normalize headers - remove quotes and HTML tags
            $cleanedHeaders = [];
            foreach ($header as $idx => $columnName) {
                // Remove quotes, BOM, HTML tags and trim whitespace
                $cleaned = trim(str_replace(['"', "'"], '', strip_tags($columnName)));
                $cleanedHeaders[$idx] = $cleaned;
            }

            // Specific column mapping for this CSV format - use case-insensitive matching
            $csvMap = [
                'full name' => 'first_name', // Will handle splitting later
                'area code' => 'area_code', // Will combine with phone number later
                'phone number' => 'phone',
                'email' => 'email',
                'skillset/subject expert' => 'bio', // Store skills in bio field
                'organization name' => 'company',
                'attach cv (pdf only)' => 'cv_resume',
                'attach profile photo' => 'photo',
                'tel' => 'tel', // Added mapping for "tel" column as fallback for phone
            ];

            // Map CSV header indices to our field names with fuzzy matching
            $mappedIndices = [];
            foreach ($cleanedHeaders as $index => $colName) {
                // Direct match
                $lowerColName = strtolower($colName);
                if (isset($csvMap[$lowerColName])) {
                    $mappedIndices[$index] = $csvMap[$lowerColName];
                    continue;
                }

                // Partial match
                foreach ($csvMap as $csvKey => $fieldName) {
                    if (stripos($lowerColName, $csvKey) !== false) {
                        $mappedIndices[$index] = $fieldName;
                        break;
                    }
                }
            }

            // Debug the header mapping
            $headerDebug = [];
            foreach ($cleanedHeaders as $index => $colName) {
                $mapped = isset($mappedIndices[$index]) ? $mappedIndices[$index] : 'Not mapped';
                $headerDebug[] = "{$colName} => {$mapped}";
            }

            // Begin transaction for data consistency
            DB::beginTransaction();
            
            $rowNumber = 1; // Track row numbers for better error reporting
            
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNumber++;

                // Skip if row is empty
                if (empty(array_filter($row))) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Empty row',
                        'data' => 'Empty row'
                    ];
                    $skipCount++;
                    continue;
                }

                // Create data array with mapped column names
                $data = [];
                foreach ($mappedIndices as $index => $fieldName) {
                    if (isset($row[$index])) {
                        // Clean the data - strip HTML tags and trim
                        $data[$fieldName] = trim(strip_tags($row[$index]));
                    }
                }

                // Handle required fields
                if (empty($data['first_name'])) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Missing required field: Full Name',
                        'data' => 'Email: ' . ($data['email'] ?? 'N/A') . '. Headers: ' . implode(', ', array_slice($cleanedHeaders, 0, 3))
                    ];
                    $skipCount++;
                    continue;
                }
                
                if (empty($data['email'])) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Missing required field: Email',
                        'data' => 'Name: ' . $data['first_name']
                    ];
                    $skipCount++;
                    continue;
                }

                // Normalize email (lowercase and trim) before checking for duplicates
                $email = strtolower(trim($data['email']));

                // Check if email already exists - use case-insensitive comparison (LOWER function)
                if (Speaker::whereRaw('LOWER(email) = ?', [strtolower($email)])->exists()) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Email already exists in database',
                        'data' => 'Email: ' . $email
                    ];
                    $skipCount++;
                    continue;
                }

                // Process full name into first and last name
                $fullNameParts = explode(' ', $data['first_name']);
                $lastName = '';

                if (count($fullNameParts) > 1) {
                    // Extract last name(s)
                    // Handle Arabic names with Al/Al-/etc.
                    $count = count($fullNameParts);

                    if ($count >= 2 && preg_match('/^(?:Al |Al-|El|El-|Bin-|Ibn)/i', $fullNameParts[$count - 2])) {
                        // If second last word is a prefix like 'Al', keep it with the last name
                        $lastName = $fullNameParts[$count - 2] . ' ' . $fullNameParts[$count - 1];
                        array_splice($fullNameParts, -2, 2);
                    } else {
                        $lastName = array_pop($fullNameParts);
                    }

                    $firstName = implode(' ', $fullNameParts);
                } else {
                    $firstName = $data['first_name'];
                    $lastName = ''; // Default empty last name
                }

                // Format first name and last name with proper capitalization
                $firstName = $this->formatName($firstName);
                $lastName = $this->formatName($lastName);

                // Combine area code and phone number if both exist
                $phone = '';
                if (!empty($data['area_code']) && !empty($data['phone'])) {
                    // Remove any plus sign from area code if it exists
                    $areaCode = trim($data['area_code'], '+');
                    $phone = '+' . $areaCode . $data['phone'];
                } elseif (!empty($data['phone'])) {
                    $phone = $data['phone'];
                } elseif (!empty($data['tel'])) {
                    // Fallback to "tel" field if phone is empty but tel exists
                    $phone = $data['tel'];
                }

                // Fix URLs - replace escaped slashes with normal slashes
                $cvUrl = !empty($data['cv_resume']) ? str_replace('\\/', '/', $data['cv_resume']) : null;
                $photoUrl = !empty($data['photo']) ? str_replace('\\/', '/', $data['photo']) : null;

                // Keep email in lowercase
                $data['email'] = $email;

                try {
                    // Create speaker with proper error handling
                    Speaker::create([
                        'user_id' => Auth::id(),
                        'first_name' => $firstName, // Properly formatted
                        'last_name' => $lastName,   // Properly formatted
                        'email' => $email,          // Normalized email
                        'phone' => $phone,          // Now with tel fallback
                        'company' => $this->formatName($data['company'] ?? ''), // Format company name
                        'job_title' => isset($data['job_title']) ? $this->formatName($data['job_title']) : null,
                        'bio' => $data['bio'] ?? null, // Skills stored in bio
                        'industry' => isset($data['industry']) ? $this->formatName($data['industry']) : null,
                        'photo' => $photoUrl, // Keep URL as is
                        'cv_resume' => $cvUrl, // Keep URL as is
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    // If there's an error creating the speaker (like duplicate email not caught by our check),
                    // log it and continue with the import instead of failing completely
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Error creating speaker: ' . $e->getMessage(),
                        'data' => 'Email: ' . $email . ', Name: ' . $firstName . ' ' . $lastName
                    ];
                    $skipCount++;
                    continue;
                }
            }
            
            fclose($handle);
            
            // Commit transaction
            DB::commit();
            
            // Clean up - delete the temporary file
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Store import results in session for detailed view
            session()->flash('import_results', [
                'success_count' => $successCount,
                'skip_count' => $skipCount,
                'skip_reasons' => $skipReasons,
                'original_headers' => $originalHeaders,
                'cleaned_headers' => $cleanedHeaders,
                'mapped_indices' => $mappedIndices,
                'header_debug' => $headerDebug,
                'mapped_fields' => array_filter([
                    'Full Name' => 'first_name & last_name',
                    'Area Code + Phone Number' => 'phone',
                    'Email' => 'email',
                    'Skillset/Subject Expert' => 'bio',
                    'Organization Name' => 'company',
                    'Attach CV (PDF Only)' => 'cv_resume (URL saved)',
                    'Attach Profile Photo' => 'photo (URL saved)',
                ]),
            ]);
            
            return redirect()->route('speakers.index')
                ->with('success', "{$successCount} speakers imported successfully. {$skipCount} entries skipped. Click for details.");
            
        } catch (\Exception $e) {
            // Roll back transaction if an error occurs
            DB::rollBack();
            
            // Clean up file on error
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            
            return redirect()->route('speakers.index')
                ->with('error', 'Error importing speakers: ' . $e->getMessage() . ' at line ' . $e->getLine());
        }
    }

    /**
     * Export speakers as CSV file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Allowed columns for sorting
        $sortable = [
            'id', 'first_name', 'last_name', 'email', 'phone',
            'company', 'job_title', 'industry', 'bio'
        ];
        $sort = request('sort', 'id');
        $direction = request('direction', 'asc');

        // Validate sort column and direction
        if (!in_array($sort, $sortable)) {
            $sort = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Filtering
        $query = Speaker::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company')) {
            $query->where('company', $request->input('company'));
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->input('industry'));
        }

        // Get all speakers with sorting
        $speakers = $query->orderBy($sort, $direction)->get();
        
        // Create temporary file
        $filename = 'speakers_export_' . date('Y-m-d_His') . '.csv';
        $tempFilePath = storage_path('app/public/' . $filename);
        
        // Create directory if it doesn't exist
        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0755, true);
        }
        
        // Open file handle
        $handle = fopen($tempFilePath, 'w');
        
        // Add CSV header row
        fputcsv($handle, [
            'ID', 'First Name', 'Last Name', 'Email', 'Phone', 
            'Company', 'Job Title', 'Industry', 'Bio'
        ]);
        
        // Add data rows
        foreach ($speakers as $speaker) {
            fputcsv($handle, [
                $speaker->id,
                $speaker->first_name,
                $speaker->last_name,
                $speaker->email,
                $speaker->phone,
                $speaker->company,
                $speaker->job_title,
                $speaker->industry,
                $speaker->bio,
                //cv
                $speaker->cv_resume,
            ]);
        }
        
        // Close file handle
        fclose($handle);
        
        // Return download response
        return response()->download($tempFilePath, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Generate update token and send email to speaker
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendUpdateLink($id)
    {
        $speaker = Speaker::findOrFail($id);

        // Generate a random token
        $token = Str::random(64);

        // Set token expiration (48 hours from now)
        $expiresAt = Carbon::now()->addHours(48);

        // Save token to speaker record
        $speaker->update([
            'update_token' => $token,
            'token_expires_at' => $expiresAt
        ]);

        // Send email with the token
        Mail::to($speaker->email)->send(new SpeakerUpdateInvitation($speaker, $token));

        return redirect()->route('speakers.show', $speaker)
            ->with('success', 'Update link has been sent to ' . $speaker->email);
    }

    /**
     * Show the form for editing speaker details with a token
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editWithToken(Request $request, $token)
    {
        $speaker = Speaker::where('update_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$speaker) {
            return redirect()->route('speakers.index')
                ->with('error', 'Invalid or expired token. Please request a new update link.');
        }

        return view('speakers.edit-public', compact('speaker', 'token'));
    }

    /**
     * Update speaker details with a token
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWithToken(Request $request, $token)
    {
        $speaker = Speaker::where('update_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$speaker) {
            return redirect()->route('speakers.index')
                ->with('error', 'Invalid or expired token. Please request a new update link.');
        }

        // Validate request
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:speakers,email,' . $speaker->id,
            'phone' => 'nullable',
            'company' => 'nullable',
            'job_title' => 'nullable',
            'bio' => 'nullable',
            'industry' => 'nullable',
            'cv_resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/speakers'), $filename);
            $request->merge(['photo' => $filename]);
        }

        if ($request->hasFile('cv_resume')) {
            $file = $request->file('cv_resume');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cv'), $filename);
            $request->merge(['cv_resume' => $filename]);
        }

        // Update speaker details
        $speaker->update($request->all());

        // Clear token after successful update
        $speaker->update([
            'update_token' => null,
            'token_expires_at' => null
        ]);

        return redirect()->route('speakers.thank-you')
            ->with('success', 'Your profile has been updated successfully.');
    }

    /**
     * Display thank you page after successful update
     *
     * @return \Illuminate\View\View
     */
    public function thankYou()
    {
        return view('speakers.thank-you');
    }
}
