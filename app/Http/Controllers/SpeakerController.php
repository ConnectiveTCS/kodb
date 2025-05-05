<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        ]);

        //handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/speakers'), $filename);
            $request->merge(['photo' => $filename]);
        }
        //get user id
        $user = Auth::user();
        //merge user id to request
        $request->merge(['user_id' => $user->id]);

        //create speaker
        Speaker::create($request->all());

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
        ]);

        //handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/speakers'), $filename);
            $request->merge(['photo' => $filename]);
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
            
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                return redirect()->route('speakers.index')->with('error', 'Could not open file.');
            }
            
            $header = fgetcsv($handle, 1000, ',');
            if (!$header) {
                fclose($handle);
                return redirect()->route('speakers.index')->with('error', 'Empty or invalid CSV file.');
            }
            
            // Begin transaction for data consistency
            DB::beginTransaction();
            
            $rowNumber = 1; // Track row numbers for better error reporting
            
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNumber++;
                // Skip if row doesn't have enough columns
                if (count($row) < count($header)) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Row has insufficient columns',
                        'data' => implode(', ', $row)
                    ];
                    $skipCount++;
                    continue;
                }
                
                $data = array_combine($header, $row);
                
                // Validate required fields
                $data = array_map('trim', $data);
                if (empty($data['first_name'])) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Missing required field: first_name',
                        'data' => 'Email: ' . ($data['email'] ?? 'N/A')
                    ];
                    $skipCount++;
                    continue;
                }
                
                if (empty($data['email'])) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Missing required field: email',
                        'data' => 'Name: ' . $data['first_name']
                    ];
                    $skipCount++;
                    continue;
                }
                
                // Check if email already exists
                if (Speaker::where('email', $data['Email'])->exists()) {
                    $skipReasons[] = [
                        'row' => $rowNumber,
                        'reason' => 'Email already exists in database',
                        'data' => 'Email: ' . $data['Email']
                    ];
                    $skipCount++;
                    continue;
                }
                
                // Process the name
                $fullName = explode(' ', $data['first_name']);
                $count = count($fullName);
                
                if ($count >= 2 && preg_match('/^Al/i', $fullName[$count-2])) {
                    // If second last word starts with "Al" (case insensitive), join it with the last name
                    $lastName = $fullName[$count-2] . ' ' . $fullName[$count-1];
                    array_pop($fullName);
                    array_pop($fullName);
                } else {
                    // Otherwise just use last word as last name
                    $lastName = array_pop($fullName);
                }
                
                $firstName = implode(' ', $fullName);
                
                // Create speaker
                Speaker::create([
                    'user_id' => Auth::id(),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $data['Email'],
                    'phone' => $data['Phone Number'] ?? null,
                    'company' => $data['Organization Name'] ?? null,
                    'job_title' => null,
                    'bio' => null,
                    'industry' => null,
                    'photo' => null,
                ]);
                
                $successCount++;
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
                'skip_reasons' => $skipReasons
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
                ->with('error', 'Error importing speakers: ' . $e->getMessage());
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
                $speaker->bio
            ]);
        }
        
        // Close file handle
        fclose($handle);
        
        // Return download response
        return response()->download($tempFilePath, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
}
