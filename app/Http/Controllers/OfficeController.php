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
    $request->validate([
        'name' => 'required|string|max:255',
        'abbreviation' => 'nullable|string|max:50'
    ]);

    Office::create([
        'name' => $row[1] ?? null,
        'abbreviation' => $row[0] ?? null,
        'head' => $row[2] ?? null,
        'designation' => $row[3] ?? null,
        'responsibility_number' => $row[4] ?? null,
    ]);

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
