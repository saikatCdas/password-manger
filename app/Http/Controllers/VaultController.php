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
    public function getVaultItems( $type){
        try{
            // exploding the string
            $parts = explode("=", $type);
            //checking is the request for category or folder
            if($parts[0] === "category"){
                if($parts[1] === 'all'){
                    $vaultItems = auth()->user()->vaults()->paginate(20);
                } else{
                    $vaultItems = auth()->user()->vaults()->where('category', ucfirst($parts[1]))->paginate(20);
                }
            }else{
                if($parts[1] === "null"){
                    $vaultItems = auth()->user()->vaults()->where('folder_id', null)->paginate(20);
                }else{
                    $vaultItems = auth()->user()->vaults()->where('folder_id', $parts[1])->paginate(20);
                }
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

    /**
     * update Vault item
     *
     * @param VaultStoreRequest $request
     * @return void
     */
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
     * delete Item or Items
     *
     * @param [type] $itemId
     * @return void
     */
    public function destroy($itemId)
    {
        try{
            // exploding and converting into an array
            $itemIds = explode(",", $itemId);

            // looping for multiple value
            foreach($itemIds as $id){
                $vaultData = Vault::whereId($id)->first();

                // Checking user has permission to delete
                if(auth()->id() === $vaultData->user_id){
                    $vaultData->delete();
                }
            }
            return response(['success'], 200);
        }catch(Throwable $e){
            return $e;
        }
    }


    /**
     * move Folder for Selected Items
     *
     * @param Request $request
     * @return void
     */
    public function moveFolder(Request $request){
        try{

            // Checking Folder Exist
            if($request->folderId !== null){
                $request->validate([
                    'folderId' => 'exists:folders,id'
                ]);
            }

            // Changing Folder
            $itemIds = $request->itemsId;
            foreach($itemIds as $id){
                $item = Vault::whereId($id)->first();
                $item['folder_id'] = $request['folderId'];
                $item->save();
            }

        }catch(Throwable $e){
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
        $data = DB::table('vaults')->get();
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($data, ['user_id', 'folder_id', 'category', 'email', 'name'])->download();
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
            dd($row, 'jdhjhd');
            Vault::create([
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
