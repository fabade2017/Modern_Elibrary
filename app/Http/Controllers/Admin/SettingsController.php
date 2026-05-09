<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
class SettingsController extends Controller
{

      public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpeg|max:3278',
        ]);

        try {
            Log::info('Logo upload attempt', [
                'file_name' => $request->file('logo')->getClientOriginalName(),
                'size' => $request->file('logo')->getSize(),
                'mime' => $request->file('logo')->getMimeType(),
            ]);

            $file = $request->file('logo');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('logos', $filename, 'public_logos'); // Store in storage/app/public/logos

            if (!Storage::disk('public_logos')->exists($path)) {
                Log::error('File storage failed', ['path' => $path]);
                return response()->json(['error' => 'Failed to store logo file'], 500);
            }

            $relativePath = $path; // e.g., logos/filename.png

            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $relativePath, 'updated_at' => now()]
            );

            $logoUrl = Storage::disk('public_logos')->url($relativePath);
            Log::info('Logo uploaded successfully', ['path' => $relativePath, 'url' => $logoUrl]);

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'logo_url' => $logoUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Logo upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }
     public function updateLogo_workigo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpeg|max:2048',
        ]);

        try {
            Log::info('Logo upload attempt', [
                'file_name' => $request->file('logo')->getClientOriginalName(),
                'size' => $request->file('logo')->getSize(),
                'mime' => $request->file('logo')->getMimeType(),
            ]);

            $file = $request->file('logo');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('public/logos', $filename); // Store in storage/app/public/logos

            if (!Storage::exists($path)) {
                Log::error('File storage failed', ['path' => $path]);
                return response()->json(['error' => 'Failed to store logo file'], 500);
            }

            $relativePath = str_replace('public/', '', $path); // e.g., logos/filename.png

            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $relativePath, 'updated_at' => now()]
            );

            $logoUrl = asset('storage/' . $relativePath);
            Log::info('Logo uploaded successfully', ['path' => $relativePath, 'url' => $logoUrl]);

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'logo_url' => $logoUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Logo upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }
     public function updateLogo_another(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpeg|max:2048',
        ]);

        try {
            Log::info('Logo upload attempt', [
                'file_name' => $request->file('logo')->getClientOriginalName(),
                'size' => $request->file('logo')->getSize(),
                'mime' => $request->file('logo')->getMimeType(),
            ]);

            $file = $request->file('logo');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('public/logos', $filename);

            if (!Storage::exists($path)) {
                Log::error('File storage failed', ['path' => $path]);
                return response()->json(['error' => 'Failed to store logo file'], 500);
            }

            $relativePath = str_replace('public/', '', $path);

            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $relativePath, 'updated_at' => now()]
            );

            Log::info('Logo uploaded successfully', ['path' => $relativePath]);

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'logo_url' => Storage::url($relativePath),
            ]);
        } catch (\Exception $e) {
            Log::error('Logo upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }
    public function updateLogopppp(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpeg|max:2048', // Max 2MB
        ]);

        try {
            // Log the upload attempt
            Log::info('Logo upload attempt', ['file' => $request->file('logo')->getClientOriginalName()]);

            // Store the file
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/logos', $filename);

            if (!$path) {
                Log::error('Failed to store logo file', ['filename' => $filename]);
                return response()->json(['error' => 'Failed to store logo file'], 500);
            }

            $relativePath = str_replace('public/', '', $path);

            // Update settings table
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $relativePath, 'updated_at' => now()]
            );

            Log::info('Logo uploaded successfully', ['path' => $relativePath]);

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'logo_url' => Storage::url($relativePath),
            ]);
        } catch (\Exception $e) {
            Log::error('Logo upload error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }
    public function updateLogoBefore(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpeg|max:2048', // Max 2MB
        ]);

        try {
            $path = $request->file('logo')->store('public/logos');
            $relativePath = str_replace('public/', '', $path);

            // Update settings table
            DB::table('settings')->updateOrInsert(
                ['key' => 'site_logo'],
                ['value' => $relativePath, 'updated_at' => now()]
            );

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'logo_url' => Storage::url($relativePath),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}