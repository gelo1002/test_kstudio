<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{AdminUser, GeneralResponse};

class AdminController extends Controller
{
    use GeneralResponse, AdminUser;

    /**
     * Show files.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles(Request $request)
    {
        if( !$this->checkPermissions(['files-view']) ){ return $this->genResponse(401); };

        return  $this->getAllFiles($request);
    }

    /**
     * Delete file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request)
    {
        if( !$this->checkPermissions(['files-delete']) ){ return $this->genResponse(401); };

        return  $this->deleteDataFile($request);
    }

}
