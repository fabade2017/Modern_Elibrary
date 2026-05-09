<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http; // 👈 THIS is missing
use App\Models\User; use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DataMapperController extends Controller
{
    public function index()
    {
                        // List DB tables for dropdown
                    
                //session(['my_value' => 123]);
                // Retrieve it anywhere in a request
                //$value = session('my_value');
                        $user = Auth::user();
                        if($user->role === 'admin')
                        {
                        
                
                            $school_id_result = DB::select("select id,school from school_admin_mappers where adminemail='".$user->email."' limit 1");
                            // $school = DB::select("select name from schools where id=".$school_id." limit 1")->get('name');
                            //{{baseUrl}}/api/external/schools/{subdomain}/students
                
                                if (!empty($school_id_result)) {
                                        $school_id = $school_id_result[0]->school; // extract the value from object

                                        // Get school name
                                        $school_result = DB::select("SELECT name, subdomain FROM schools WHERE id = ? LIMIT 1", [$school_id]);

                                        $school = !empty($school_result) ? $school_result[0]->name : null;
                                        $school_domain = !empty($school_result) ? $school_result[0]->subdomain : null;
                                            $superdomain = env('COMMANDSCHOOL_BASE_URL').'/api/external/schools/'.$school_domain.'/students';
                                            $school_domain_clean = str_replace('.', '', $school_domain);

                                    $tables = DB::select("SHOW TABLES LIKE '%".$school_domain_clean."%'");
                            } else {
                                $school = null; $school_domain = null;
                            } 
                    }
                        if($user->role === 'superadmin')
                        {
                            $tables = DB::select('SHOW TABLES');
                            $school = "All"; $school_domain = "All"; $school_domain_clean = "All"; $superdomain="All";
                        }
                        $tables = array_map('current', $tables);
                session(['my_school_domain' =>  $school]);session(['my_school_domain' =>  $school_domain]);  
                session(['my_school_domain_clean' =>  $school_domain_clean]);
                        return view('admin.data_mapper.index', compact('tables', 'school','school_domain','superdomain'));
    }

    public function fetchApi(Request $request)
    {
    //   return back()->with('success', "Surely here"); 
       $url = $request->input('api_url');
     
      if($url ==="")
      {  return view('admin.data_mapper.index', compact('tables'));
      }
$urlp = "https://portal.commandschools.sch.ng/api/external/school-domains";


                if($url === $urlp){
                        $response = Http::withHeaders([
                            'X-Public-Key' => env('COMMANDSCHOOL_PUBLIC_KEY'),
                            'X-Secret-Key' => env('COMMANDSCHOOL_SECRET_KEY'),
                            'Accept'       => 'application/json',
                        ])->get($url);
                            if ($response->failed()) {
                            return back()->withErrors('API request failed with status '.env('COMMANDSCHOOL_PUBLIC_KEY').$response->status());
                        }
                    $data2 = $response->json();
                    $data1 = $data2['data'];
                } 
                else
                {
     
                    $response = Http::withHeaders([
                            'X-Public-Key' => env('COMMANDSCHOOL_PUBLIC_KEY'),
                            'X-Secret-Key' => env('COMMANDSCHOOL_SECRET_KEY'),
                            'Accept'       => 'application/json',
                        ])->get($url);
                            if ($response->failed()) {
                                                           $var1=$response->status();$val1=env('COMMANDSCHOOL_PUBLIC_KEY').time();
DB::insert('INSERT INTO dad1 (var1, val1) VALUES (?, ?)', [
    $val1,
    $var1,
]);
                            return back()->withErrors('API request failed with status '.$response->status());
                        }
            
                    $data2 = $response->json();
                    $data1 = $data2['data'];
                    /*
                    $response = json_decode(file_get_contents($url), true);
                        if ($response->failed()) {
                            return back()->withErrors('API request failed with status '.$response->status());
                        }
                    $data1 = $response->json();*/
                }
   
    
             $data = $data1;
       /// return view('admin.school_domains.index', compact('data'));


        if (!$data || !is_array($data)) {
            return back()->withErrors('Invalid API response.');
        }
$value = session('my_school_domain_clean');

        // $user = Auth::user();
// if($user->role_id ===2)
// {

        $tableName = $value;
// }
// elseif($url === $urlp){
// $tableName ='schools';
// }

//return view('admin.data_mapper.index', compact('tables'));
        // Get first row for schema
        $firstRow = $data[0] ?? [];
      if (Schema::hasTable($tableName)) 
        {
                            // Generate new name with timestamp
                            $timestamp = now()->format('Ymd_His'); // e.g. 20250827_093010
                            $newName = $tableName . '_' . $timestamp;

                            // Rename the table
                            Schema::rename($tableName, $newName);
                                $act = $this->dropOldTables($tableName);

                        // return "Renamed {$tableName} to {$newName}";
        }
Schema::create($tableName, function ($table) use ($firstRow) {
    // If your JSON already has "id" (UUID style), don’t create auto-increment
    if (!array_key_exists('id', $firstRow)) {
        $table->id();
    }

    foreach ($firstRow as $key => $value) {
        // Handle "id" specially (UUIDs)
        if ($key === 'id' && array_key_exists('id', $firstRow)) {
            $table->string('id', 50)->nullable(); // UUID fits in 36 chars
            continue;
        }

        // Decide column type
        if (is_numeric($value)) {
            $table->bigInteger($key)->nullable();
        } elseif (is_string($value)) {
            if (strlen($value) > 255) {
                $table->text($key)->nullable();
            } else {
                $table->string($key, 255)->nullable();
            }
        } else {
            $table->string($key, 255)->nullable();
        }
    }

    $table->timestamps();
});

$columns = Schema::getColumnListing($tableName); // Get all columns in the table
foreach ($data as &$row) {
    if (in_array('created_at', $columns)) {
        $row['created_at'] = now();
    }
    if (in_array('updated_at', $columns)) {
        $row['updated_at'] = now();
    }
}
//DB::table($tableName)->insert($data);
        // Insert data
        foreach ($data as $row) {
        
            DB::table($tableName)->insert($row);
        }
if($url != $urlp){
$this->syncUsers($tableName);
}

        return back()->with('success', "Table '$tableName' created and data inserted.")
                     ->with('api_table', $tableName);
    }

    public function dropOldTables($baseName)
{
    // 1. Get all tables in the database
    $tables = collect(DB::select("SHOW TABLES"))
        ->map(function ($row) {
            return array_values((array)$row)[0]; // extract table name string
        });

    // 2. Filter tables that contain the base name
    $matchingTables = $tables->filter(function ($table) use ($baseName) {
        return str_contains($table, $baseName);
    })->values();

    // 3. Sort by timestamp (if appended at the end)
    $sorted = $matchingTables->sortBy(function ($table) {
        // Expect format like: students_20250827_093010
        preg_match('/_(\d{8}_\d{6})$/', $table, $matches);
        return $matches[1] ?? '00000000_000000'; // fallback for non-matching
    });

    // 4. Keep the latest, delete older ones
    if ($sorted->count() > 1) {
        $latest = $sorted->last(); // newest
        $toDelete = $sorted->slice(0, -1); // all except newest

        foreach ($toDelete as $oldTable) {
            Schema::dropIfExists($oldTable);
        }

        return [
            'kept' => $latest,
            'deleted' => $toDelete->all(),
        ];
    }

    return ['kept' => $sorted->first(), 'deleted' => []];
}
public function saveMapping(Request $request)
{
    $apiTable = $request->input('api_table');
    $targetTable = $request->input('target_table');
    $mappings = $request->input('mappings', []); // default to empty array

    if (empty($mappings)) {
        return back()->with('error', 'No mappings provided.');
    }

    // fetch API table rows
    $apiData = DB::table($apiTable)->get();

    foreach ($apiData as $row) {
        $insert = [];

        foreach ($mappings as $apiCol => $targetCol) {
            if (isset($row->$apiCol)) {
                $insert[$targetCol] = $row->$apiCol;
            }
        }

        if (!empty($insert)) {
            DB::table($targetTable)->insert($insert);
        }
    }

    return back()->with('success', "Data mapped into '$targetTable'.");
}

                    //     public function saveMapping(Request $request)
                    // {
                    //     $apiTable    = $request->input('api_table');
                    //     $targetTable = $request->input('target_table');
                    //     $mappings    = $request->input('mappings', []); // default to []

                    //     if (empty($mappings)) {
                    //         return back()->with('error', 'No mappings were provided.');
                    //     }

                    //     $apiData = DB::table($apiTable)->get();

                    //     foreach ($apiData as $row) {
                    //         $insert = [];
                    //         foreach ($mappings as $apiCol => $targetCol) {
                    //             if (!empty($targetCol) && isset($row->$apiCol)) {
                    //                 $insert[$targetCol] = $row->$apiCol;
                    //             }
                    //         }
                    //         if (!empty($insert)) {
                    //             DB::table($targetTable)->insert($insert);
                    //         }
                    //     }

                    //     return back()->with('success', "Data mapped into '$targetTable'.");
                    // }

// public function saveMapping(Request $request)
// {
//     $apiTable    = $request->input('api_table');
//     $targetTable = $request->input('target_table');
//     $mappings    = $request->input('mappings', []); // default to []

//     if (empty($mappings)) {
//         return back()->with('error', 'No mappings were provided.');
//     }

//     $apiData = DB::table($apiTable)->get();

//     foreach ($apiData as $row) {
//         $insert = [];
//         foreach ($mappings as $apiCol => $targetCol) {
//             if (!empty($targetCol) && isset($row->$apiCol)) {
//                 $insert[$targetCol] = $row->$apiCol;
//             }
//         }
//         if (!empty($insert)) {
//             DB::table($targetTable)->insert($insert);
//         }
//     }

//     return back()->with('success', "Data mapped into '$targetTable'.");
// }
public function getColumns(Request $request)
{
    $table = $request->input('api_table');
$table2 = $request->input('target_table');
    if (!$table) {
        return response()->json(['columns' => []]);
    }

    try {
        $api_columns = \Schema::getColumnListing($table);
        $target_columns = \Schema::getColumnListing($table2);
        return response()->json(['api_columns' => $api_columns,'target_columns' => $target_columns]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function saveMappingGGGGG(Request $request)
    {
        $apiTable = $request->input('api_table');
        $targetTable = $request->input('target_table');
        $mappings = $request->input('mapping'); // e.g. ['api_col' => 'target_col']

        $apiData = DB::table($apiTable)->get();

        foreach ($apiData as $row) {
            $insert = [];
            foreach ($mappings as $apiCol => $targetCol) {
                $insert[$targetCol] = $row->$apiCol;
            }
            DB::table($targetTable)->insert($insert);
        }

        return back()->with('success', "Data mapped into '$targetTable'.");
    }

    public function deleteData(Request $request)
    {
        $apiTable = $request->input('api_table');
        $targetTable = $request->input('target_table');

        if (Schema::hasTable($apiTable)) {
            DB::table($apiTable)->truncate();
        }

        if (Schema::hasTable($targetTable)) {
            DB::table($targetTable)->truncate();
        }

        return back()->with('success', "Data cleared in both '$apiTable' and '$targetTable'.");
    }

        //     public function syncUsers($tablename)
        // {
        //     // Get all records from the dynamic table
        //     $records = DB::table($tablename)->get();

        //     foreach ($records as $record) {
        //         DB::table('users')->updateOrInsert(
        //             [
        //                 'name'  => $record->name,
        //                 'email' => $record->email,
        //             ],
        //             [
        //                 'password' => Hash::make($record->admission_number),
        //             ]
        //         );
        //     }

        //     return "Users synced from {$tablename}";
        // }
        public function syncUsers($tablename)
{
    // Step 1: Build maps for lookup tables
    $categoryMap = $this->buildMap($tablename, 'category', 'categories');
    $classMap = $this->buildMap($tablename, 'class', 'classes');
   // $roleMap = $this->buildMap($tablename, '', 'roles');
 //   $groupMap = $this->buildMap($tablename, 'department', 'groups');
 $departmentMap = $this->buildMap($tablename, 'department', 'departments');
  $armMap = $this->buildMap($tablename, 'arm', 'arms');
    // Step 2: Sync records to users
    $records = DB::table($tablename)->get();
    $count = 0;

    foreach ($records as $record) {
        // Construct name
        $nameParts = [$record->surname ?? '', $record->firstname ?? '', $record->othername ?? ''];
        $name = trim(implode(' ', array_filter($nameParts, fn($part) => !empty($part))));

        // Skip if email is empty
        if (empty($record->email)) {
            \Log::warning("Skipping sync for record with admissionno: {$record->admissionno} due to empty email");
            continue;
        }

        // Map IDs (use null if not found)
        $categoryId = $categoryMap[$record->category] ?? null;
        $classId = $classMap[$record->class] ?? null;
        $departmentId = $departmentMap[$record->department] ?? null;
        $groupId = $groupMap[$record->department] ?? null;
        $armId =  $armMap[$record->arm] ?? null;
        // Upsert user
        DB::table('users')->updateOrInsert(
            ['email' => $record->email], // Condition for update
            [
                'name' => $name,
                'password' => Hash::make($record->admission_number), // Only set for new users
              'category_id' =>$categoryId,
                'class_id' => $classId,
                'role_id' => 3,
                'active' => 1,
                'role' => 'student',
                'group_id' => 0,
                'department_id' => $departmentId,
               'arm_id' => $armId,
                'updated_at' => now(),
                'created_at' => now(), // Only used for inserts

            ]
        );

        $count++;
    }

    // Log success and return message
    Log::info("Synchronized $count records from $tablename to users table.");
    return "Synchronized $count records from $tablename to users table.";
}

/**
 * Build a map of value => ID for a lookup table.
 *
 * @param string $sourceTable Source table name
 * @param string $column Column to extract unique values from
 * @param string $lookupTable Lookup table name (e.g., 'categories')
 * @return array Map of value => ID
 */
protected function buildMap(string $sourceTable, string $column, string $lookupTable): array
{
    // Get unique values from source table
    $uniqueValues = DB::table($sourceTable)
        ->select($column)
        ->distinct()
        ->pluck($column)
        ->filter(fn($value) => !empty($value))
        ->toArray();

    // Get existing entries in lookup table
    $existing = DB::table($lookupTable)
        ->pluck('id', 'name')
        ->toArray();

    // Insert new values and build map
    $map = $existing;
    foreach (array_diff($uniqueValues, array_keys($existing)) as $value) {
        $id = DB::table($lookupTable)->insertGetId(['name' => $value]);
        $map[$value] = $id;
    }

    return $map;
}
}
