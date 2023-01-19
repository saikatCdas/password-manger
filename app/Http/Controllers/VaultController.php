<?php

namespace App\Http\Controllers;

use App\Http\Requests\VaultStoreRequest;
use App\Http\Resources\VaultResource;
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
    public function getVaultItems( string $type){
        try{
            if($type === 'all'){
                $vaultItems = auth()->user()->vaults()->paginate(20);
            } else{
                $vaultItems = auth()->user()->vaults()->where('name', ucfirst($type))->paginate(20);
            }
            return VaultResource::collection($vaultItems);
        } catch(Throwable $e){
            return $e;
        }
    }

    /**
     * get item from vault by id
     *
     * @param [type] $id
     * @return void
     */
    public function getItem($id){
        $item = Vault::whereId($id)->first();

        if($item->user_id === auth()->id()){
            return new VaultResource($item);
        }

        return 'Unauthorized Action';
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
            $vaultIntiData = $request->validated();

            // if request has folder
            $vaultIntiData['folder_id'] = $this->getFolderId($request);

            $vaultIntiData['user_id'] = auth()->id();

            // Create a vault
            $vaultData = Vault::create($vaultIntiData);

            return $vaultData;

        } catch( Throwable $e ) {
            return $e;
        }
    }

    public function update(VaultStoreRequest $request){
        try{

            // Validate form data
            $vaultIntiData = $request->validated();

            // if request has folder
            $vaultIntiData['folder_id'] = $this->getFolderId($request);

            $vaultIntiData['user_id'] = auth()->id();

            // update vault
            Vault::whereId($request->id)->update($vaultIntiData);

            return 'success';

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

    private function getFolderId($request){
        // if request has folder
        if($request->folder){

            // get the Folder id
            $folder = Folder::where('name', $request->folder)->first();
            return $folder->id;

        }else{
            return null;
        }
    }


}
