<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Core\UserFile;
use App\Mail\PhotoNotification;
use Auth, Mail, Config, Exception;

trait AdminUser 
{
    private function getAllFiles(Request $request)
    {
        try {
            $user_file = UserFile::all();

            return $this->genResponse(200, $user_file, null, 'show-files');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }

    private function deleteDataFile(Request $request)
    {
        $validator = Validator::make($request->all(), UserFile::getValidationRules('delete'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }

        try {
            $id = decrypt($request->eid);
        } 
        catch (DecryptException $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }

        if (!$user_file = UserFile::find($id)) {
            return $this->genResponse(404);
        }
        try {
            $user = $user_file->user;

            $user_file->delete();

            $this->sendEmailPhotoNotification($user);

            return $this->genResponse(200, null, null, 'delete-files');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }

    private function sendEmailPhotoNotification($user)
    {
        $mail_content = (object)[
            "first_name"    => $user->first_name,
            "last_name"     => $user->last_name,
        ];

        Mail::to($user->email)->queue( new PhotoNotification($mail_content) );
    }
}