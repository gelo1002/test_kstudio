<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{RegisterUser, GeneralResponse};

class UserController extends Controller
{
    use GeneralResponse, RegisterUser;

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function create(Request $request)
    {
        return  $this->doCreateUser($request);        
    }

    /**
     * Verify email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function verifyEmail(Request $request)
    {
        return  $this->doVerifyEmail($request); 
    }

    /**
     * Show profile information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function getProfile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-profile']) ){ return $this->genResponse(401); };

        return  $this->getProfileInfo(); 
    }

    /**
     * Update password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function updateProfile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-profile']) ){ return $this->genResponse(401); };

        return  $this->updateProfileInfo($request);
    }

    /**
     * Update password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function updatePassword(Request $request)
    {
        if( !$this->checkPermissions(['view-my-profile']) ){ return $this->genResponse(401); };

        return  $this->doUpdatePassword($request);
    }

    /**
     * New resource -> new file upload.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-files']) ){ return $this->genResponse(401); };

        return  $this->doCreateFile($request);
    }

    /**
     * Show files.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles(Request $request)
    {
        if( !$this->checkPermissions(['view-my-files']) ){ return $this->genResponse(401); };

        return  $this->getAllFiles($request);
    }

    /**
     * Show file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-files']) ){ return $this->genResponse(401); };

        return  $this->getDataFile($request);
    }
    
    /**
     * Delete file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-files']) ){ return $this->genResponse(401); };

        return  $this->deleteDataFile($request);
    }

    /**
     * Delete profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfile(Request $request)
    {
        if( !$this->checkPermissions(['view-my-files']) ){ return $this->genResponse(401); };

        return  $this->deleteProfileInfo($request);
    }
}
