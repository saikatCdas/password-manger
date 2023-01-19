<?php

namespace App\Http\Controllers;

use App\Http\Requests\VaultStoreRequest;
use App\Models\Folder;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class VaultController extends Controller
{
    /**
     * get items from vault
     *
     * @param [type] $type
     * @return void
     */
    public function getVaultItems( $type){
        if($type === 'all'){
            return response(auth()->user()->vaults()->paginate(20));
        }
        return 'success';
    }

    /**
     * Create items in vault
     *
     * @param VaultStoreRequest $request
     * @return void
     */
    public function store( VaultStoreRequest $request ){
        try{

            // Validate form data
            $vaultData = $request->validated();

            if($request->folder){
                // get the Folder id
                $folder = Folder::where('name', $request->folder)->first();
                $folderId = $folder->id;
                $vaultData['folder_id'] = $folderId;
            }
            $vaultData['user_id'] = auth()->id();

            // Create a vault
            $vaultData = Vault::create($vaultData);

            return $vaultData;

        } catch( Throwable $e ) {
            return $e;
        }
    }

    /**
     * Export csv file from Database
     *
     * @return void
     */
    public function export()
    {
        $data = DB::table('users')->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($data, ['name', 'email'])->download();
    }

    /**
     * import csv file
     *
     * @param Request $request
     * @return void
     */
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
