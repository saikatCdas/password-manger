<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VaultController extends Controller
{
    public function export()
    {
        $data = DB::table('users')->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($data, ['name', 'email'])->download();
    }

    public function import(Request $request)
    {
        $file = $request->file('csv_file');
        $csvData = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
            // return $row[0];
            User::create([
                'name' => $row[0],
                'email' => $row[1],
                'password' => 'at@S1998'
            ]);
        }
        // do something with the csv data
        return $csv;
    }


}
