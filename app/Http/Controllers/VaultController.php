<?php

namespace App\Http\Controllers;

use App\Http\Requests\VaultStoreRequest;
use App\Http\Resources\VaultResource;
use App\Models\Folder;
use App\Models\User;
use App\Models\Vault;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        } catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }

    /**
     * get item from vault by id
     *
     * @param [type] $id
     * @return void
     */
    public function getItemById($id){
        try{
            $item = Vault::whereId($id)->first();

            if($item->user_id === auth()->id()){
                return new VaultResource($item);
            }

            return abort(403,'Unauthorized Action');
        } catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }


    /**
     * Search into vault
     *
     * @param String $searchInp
     * @return void
     */
    public function search( String $searchInp)
    {
        $result = Vault::latest()->where('user_id', auth()->id())
                    ->where('name', 'like', '%' . $searchInp . '%')
                    ->orWhere('email', 'like', '%' . $searchInp . '%')
                    ->orWhere('category', 'like', '%' . $searchInp . '%')
                    ->orWhere('user_name', 'like', '%' . $searchInp . '%')
                    ->orWhere('url', 'like', '%' . $searchInp . '%')
                    ->orWhere('notes', 'like', '%' . $searchInp . '%')->paginate(20);

        return VaultResource::collection($result);
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
            $vaultIntiData['folder_id'] = $this->getFolderId($request->folder);

            $vaultIntiData['user_id'] = auth()->id();

            // Create a vault
            $vaultData = Vault::create($vaultIntiData);

            return response($vaultData, 200);

        } catch(Exception $e){
            return response($e->getMessage(), 500);
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
            $vaultIntiData['folder_id'] = $this->getFolderId($request->folder);

            $vaultIntiData['user_id'] = auth()->id();

            // update vault
            Vault::whereId($request->id)->update($vaultIntiData);

            return 'success';

        }catch(Exception $e){
            return response($e->getMessage(), 500);
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
        }catch(Exception $e){
            return response($e->getMessage(), 500);
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

            return response('success', 200);

        }catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }
    /**
     * Export csv file from Database
     *
     * @return void
     */

    public function export(Request $request)
    {
        try{
            $fileName = 'vaultItems.csv';
        $vaultItems = Vault::where('user_id', auth()->id())->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('user_id', 'folder_id', 'category', 'email', 'name', 'user_name' , 'password', 'url','card_holder_name',  'card_number',  'card_expiration_date',  'card_security_code', 'notes');

        $callback = function() use($vaultItems, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($vaultItems as $item) {
                $columns['user_id']  = $item->user_id;
                $columns['folder_id']    = $item->folder_id;
                $columns['category']    = $item->category;
                $columns['email']  = $item->email;
                $columns['name']  = $item->name;
                $columns['user_name']  = $item->user_name;
                $columns['password']  = $item->password;
                $columns['url']  = $item->url;
                $columns['card_holder_name']  = $item->card_holder_name;
                $columns['card_number']  = $item->card_number;
                $columns['card_expiration_date']  = $item->card_expiration_date;
                $columns['card_security_code']  = $item->card_security_code;
                $columns['notes']  = $item->notes;

                fputcsv($file, array($columns['user_id'], $columns['folder_id'], $columns['category'], $columns['email'], $columns['name'], $columns['user_name'], $columns['password'], $columns['url'], $columns['card_holder_name'], $columns['card_number'], $columns['card_expiration_date'], $columns['card_security_code'], $columns['notes']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        } catch (Exception $e){
            return response($e->getMessage(), 500);
        }
    }

    /**
     * import csv file
     *
     * @param Request $request
     * @return void
     */
    public function import(Request $request)
    {
        try{
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file->getRealPath());
            $rows = array_map('str_getcsv', explode("\n", $csvData));

            $count = count($rows);
            foreach ($rows as $index => $row)
            {
                if($index == 0 || (count($row) <= 1)) {
                    continue;
                }
                $credential =  array_combine($rows[0], $row);

                $credentialValidation = $this->importVaultDataValidation($credential);

                if(!$credentialValidation){
                    return response('Validation Error', 402);
                }

                if($credential['folder_name']){
                    // if request has folder
                    $credential['folder_id'] = $this->getFolderId($credential['folder_name']);
                }elseif ($credential['folder_id']) {
                    // if request has folder_id
                    $folderId = Folder::whereId($credential['folder_id'])->exists();
                    $credential['folder_id'] = $folderId ? $credential['folder_id'] : null;
                }
                else{
                    $credential['folder_id'] = null;
                }

                $credential['user_id'] = auth()->id();
                Vault::create($credential);
            }
            return response('success', 200);
        } catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }

    private function importVaultDataValidation($credential)
    {
        try{

            // checking category required and string
            if ((strlen($credential['category']) <= 0 ) || !(is_string($credential['category']))) {
            return false;
            }

            // checking name required and string
            elseif ((strlen($credential['name']) <= 0 ) || !(is_string($credential['name']))) {
            return false;
            }

            // checking user_name string
            elseif (!(is_string($credential['user_name']))) {
                return false;
            }

            // checking email string
            elseif (!(is_string($credential['email']))) {
                return false;
            }

            // checking password string
            elseif (!(is_string($credential['password']))) {
                return false;
            }

            // checking category url
            elseif (!(is_string($credential['url']))) {
                return false;
            }

            // checking category card_holder_name
            elseif (!(is_string($credential['card_holder_name']))) {
                return false;
            }

            // checking category card_number
            elseif (!(is_string($credential['card_number']))) {
                return false;
            }

            // checking category card_expiration_date
            elseif (!(is_string($credential['card_expiration_date']))) {
                return false;
            }

            // checking category card_security_code
            elseif (!(is_string($credential['card_security_code']))) {
                return false;
            }

            // checking category string
            elseif (!(is_string($credential['notes']))) {
                return false;
            } else return true;

        } catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }

    private function getFolderId($name){
        // if request has folder
        try{
            if($name){

                $exists = Folder::where('name', $name)->exists();
                if($exists){
                    $folder = Folder::where('name', $name)->first();
                } else{
                    if(is_string($name)){
                        $credential['name'] = $name;
                        $credential['user_id'] = auth()->id();
                        $folder = Folder::create($credential);
                    }
                }
                // get the Folder id
                return $folder->id;

            }else{
                return null;
            }
        } catch (Exception $e){
            return response($e->getMessage(), 500);
        }
    }


}
