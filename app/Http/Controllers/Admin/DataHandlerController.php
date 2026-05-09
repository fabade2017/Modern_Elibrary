<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use App\Models\StudentData;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class DataHandlerController extends Controller
{
    public function index()
    {
        return view('admin.data-handler'); // View file: resources/views/data-handler.blade.php
    }

    public function loadFromUrlvvv(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        try {
            $response = Http::get($request->url);
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch data from URL'], 400);
            }

            $data = $response->json();
            if (!is_array($data)) {
                return response()->json(['error' => 'Invalid data format (expected array of objects)'], 400);
            }

            // Optional: Truncate staging table before load
            StudentData::truncate();

            $inserted = 0;
            foreach ($data as $item) {
                StudentData::create($item); // Assumes keys match fillable columns
                $inserted++;
            }

            return response()->json(['success' => true, 'message' => "$inserted records loaded into studentData"]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
public function loadFromUrl(Request $request)
{
    $request->validate(['url' => 'required|url']);

    try {
        // Set timeout and handle CORS if needed
        $response = Http::timeout(30)->get($request->url);
        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to fetch data from URL',
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200), // Partial response for debugging
            ], 400);
        }

        $data = $response->json();
        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid data format (expected array of objects)', 'body' => substr($response->body(), 0, 200)], 400);
        }

        // Truncate staging table before load (optional)
        StudentData::truncate();

        $inserted = 0;
        foreach ($data as $item) {
            // Ensure only fillable fields are used
            StudentData::create(array_intersect_key($item, array_flip((new StudentData)->getFillable())));
            $inserted++;
        }

        return response()->json(['success' => true, 'message' => "$inserted records loaded into studentData"]);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        return response()->json(['error' => 'Connection error: Could not reach the URL', 'details' => $e->getMessage()], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}
    public function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $tableList = [];
        foreach ($tables as $table) {
            $tableName = current($table); // MySQL returns as object with key like 'Tables_in_db'
            if ($tableName !== 'studentData') { // Exclude source
                $tableList[] = $tableName;
            }
        }
        return response()->json($tableList);
    }

    public function getColumns(Request $request)
    {
        $request->validate(['table' => 'required|string']);
        $sourceColumns = Schema::getColumnListing('studentData');
        $targetColumns = Schema::getColumnListing($request->table);

        return response()->json([
            'source' => $sourceColumns,
            'target' => $targetColumns,
        ]);
    }

    public function transferData(Request $request)
    {
        $request->validate([
            'target_table' => 'required|string',
            'mapping' => 'required|array', // e.g., ['target_col1' => ['source_colA', 'source_colB'], ...]
        ]);

        try {
            $sourceData = StudentData::all();
            $targetTable = $request->target_table;
            $mapping = $request->mapping;

            $success = 0;
            $failed = 0;
            $errors = [];

            foreach ($sourceData as $row) {
                $newRow = [];
                foreach ($mapping as $targetCol => $sourceCols) {
                    if (!is_array($sourceCols)) $sourceCols = [$sourceCols];
                    $values = [];
                    foreach ($sourceCols as $sourceCol) {
                        if (isset($row->$sourceCol)) {
                            $values[] = $row->$sourceCol;
                        }
                    }
                    $newRow[$targetCol] = implode(' ', $values); // Combine with space
                }

                try {
                    DB::table($targetTable)->insert($newRow);
                    $success++;
                } catch (Exception $e) {
                    $failed++;
                    $errors[] = "Failed to insert row: " . $e->getMessage();
                }
            }

            // Optional: Truncate studentData after transfer
            // StudentData::truncate();

            return response()->json([
                'success' => true,
                'report' => [
                    'successful' => $success,
                    'failed' => $failed,
                    'errors' => $errors,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}