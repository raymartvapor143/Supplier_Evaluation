<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OfficeImport;

class OfficeController extends Controller
{
public function count()
{
    return response()->json([
        'count' => Office::count()
    ]);
}

public function list()
{
    return Office::orderBy('name')->get();
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'abbreviation' => 'nullable|string|max:50',
        'head' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'responsibility_number' => 'nullable|string|max:100',
    ]);

    Office::create($validated);

    return response()->json(['success' => true]);
}

    public function delete($id)
    {
        Office::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new OfficeImport, $request->file('file'));

        return response()->json([
            'message' => 'Offices imported successfully'
        ]);
    }


}
