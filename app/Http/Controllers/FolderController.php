<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Get All Folder
     *
     * @return void
     */
    public function allFolder(){
        $folders = auth()->user()->folders()->get();

        return $folders;

    }

    /**
     * Create a Folder
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request){
        $folderName = $request->validate([
            'name' => 'required|string',
        ]);
        $folderName['user_id'] = auth()->id();
        Folder::create($folderName);

        return $this->allFolder();
    }
}
