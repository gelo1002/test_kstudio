<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\User;
use App\Models\Core\{Role, UserFile};
use App\Mail\{VerifyEmail, AccountActivated};
use Auth, DB, Mail, Config, Exception;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

trait RegisterUser 
{
    private $ext_photo = ['jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'];

    public function doCreateUser(Request $request)
    {
        $validator = Validator::make($request->all(), User::getValidationRules('create'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }

        $email_hash = md5($request->email);
        
        if ($duplicate = User::where('email_hash',$email_hash)->first()) {
            return $this->genResponse(400,null,'El usuario ya existe');
        }
        $role = Role::where('key','platform_user')->first();

        $elements = DB::transaction(function () use ($request, $role, $email_hash) {

            $user = User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'nickname'      => $request->nickname,
                'email'         => $request->email,
                'email_hash'    => $email_hash,
                'password'      => bcrypt($request->password),
                'role_id'       => $role->id,
                'active'        => 0, // must verify email in next step to activate
                'status'        => 0
            ]);

            $user->encrypt_id = encrypt($user->id);
            $user->save();
 
            $this->sendEmailVerificationLink($request->email, $request->first_name, $request->last_name, $user->encrypt_id);

            return $user;
        });

        return $this->genResponse(201, $elements, null, 'user created');
    }

    public function doVerifyEmail(Request $request)
    {
        try {
            $user_id = decrypt($request->eid);
        } 
        catch (DecryptException $e) {
            return $this->genResponse(400, 'ID invalido' );
        }

        $user = User::find($user_id);
        
        if ($user) {
            if (!$user->email_verified_at) {
                $now = new \DateTime;
                $user->email_verified_at = $now;
                $user->active = 1;
                $user->save();
            }
            else {
                return $this->genResponse(400, 'El correo ya fue verificado' );
            }

            $this->sendEmailAccountActivated($user);

            return $this->genResponse(200, null, 'Correo verificado','verify-email');
          }
          
          return $this->genResponse(404, 'Usuario no encontrado' );
    }

    private function getProfileInfo()
    {
        $user = Auth::user();

        try {
            $data = (object) [
                'first_name'    => $user->first_name,
                'last_name'     => $user->last_name,
                'nickname'      => $user->nickname,
                'email'         => $user->email,
                'avatar'        => $user->avatar,
            ];
            return $this->genResponse(200, $data, null, 'show-profile');
        }
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }

    }

    private function updateProfileInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), User::getValidationRules('update'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }
        try {
            
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->nickname     = $request->nickname;
            $user->save();
            
            return $this->genResponse(200, $request->all(), null, 'Update profile');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }

    private function doUpdatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), User::getValidationRules('update_password'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }
        
        try {
            $user->password = bcrypt($request->new_password);
            $user->save();
            
            return $this->genResponse(200, null, null, 'Update password');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }
    // TODO: pending
    private function doUpdateUserAvatar(Request $request)
    {
        return $this->genResponse(400, null, null, 'pending....');
    }
    // TODO: look over
    private function doCreateFile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), UserFile::getValidationRules('create'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }
        if (!in_array($request->file('file')->extension(), $this->ext_photo)) {
            return $this->genResponse(400, "No es el tipo de Archivo permitido, solo se aceptan jpeg, jpg y png" );
        }

        // $exif = \exif_read_data($request->file('file'), 0, true);
        // dd($exif);

        $file_path = Storage::disk('local')->putFile($user->encrypt_id, new File($request->file()['file']));
        
        $item = DB::transaction(function () use ($user, $request, $file_path) {
            $user_file = UserFile::create([
                'user_id'       => $user->id,
                'file_path'     => $file_path,
                'description'   => $request->description,
            ]);

            $user_file->encrypt_id = encrypt($user_file->id);
            $user_file->save();

            return $user_file;
        });

        return $this->genResponse(200, null, null, 'Archivo cargado');
    }

    private function getAllFiles(Request $request)
    {
        $user = Auth::user();

        if (!$user_file = $user->userFile) {
            return $this->genResponse(404);
        }
        try {
            $files = [];

            foreach ($user->userFile as $file) {
                array_push($files, $file);
            }

            return $this->genResponse(200, $files, null, 'show-files');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }
    // TODO: look over
    private function getDataFile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), UserFile::getValidationRules('file'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }

        if (!$user_file = $user->userFile) {
            return $this->genResponse(404);
        }

        try {
            $valid_file = Storage::disk('local')->exists($request->file_path);
            
            if ($valid_file) {

                $mimeType   = Storage::disk('local')->getMimetype($request->file_path);
                $file       = Storage::disk('local')->get($request->file_path);

                $response = \Response::make($file, 200);
                $response->header("Content-Type", $mimeType);
                return $response;

            }
            else {
                return $this->genResponse(404);
            }
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }

    private function deleteDataFile(Request $request)
    {
        $user = Auth::user();

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
            if ($user->id == $user_file->user->id) {
                $user_file->delete();
                return $this->genResponse(200, null, null, 'delete-files');
            }
            return $this->genResponse(401);
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }

    private function deleteProfileInfo(Request $request)
    {
        try {
            $user = Auth::user();

            $user->userFile->each->delete();
            $user->delete();

            return $this->genResponse(200, null, null, 'delete-user');
        } 
        catch (Exception $e) {
            return $this->genResponse(400, null, $e->getMessage());
        }
    }
    
    //******************MAIL******************
    public function sendEmailVerificationLink($email, $first_name, $last_name, $user_eid)
    {
        $mail_content = (object)[
            "first_name"            =>  $first_name,
            "last_name"             =>  $last_name,
            "verify_link"           =>  config('kokonut.front_url').'/api/v1/register/verify-email?eid='.$user_eid,
        ];

        //send mail
        Mail::to($email)->queue( new VerifyEmail($mail_content) );
    }

    public function sendEmailAccountActivated($user)
    {
        $mail_content = (object)[
            "first_name"    => $user->first_name,
            "last_name"     => $user->last_name,
            "login_link"    => config('kokonut.front_url')."/login",
        ];

        Mail::to($user->email)->queue( new AccountActivated($mail_content) );
    }
}