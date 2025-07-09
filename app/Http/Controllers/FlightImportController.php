<?php

namespace App\Http\Controllers;

use App\Imports\FlightImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FlightImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,xls']
        ]);
        Excel::import(new FlightImport, $request->file('file'));
        return response(['success' => true, 'data' => $request]);
    }
}
