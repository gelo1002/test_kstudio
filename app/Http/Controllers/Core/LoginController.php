<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\GeneralResponse;
use App\Models\User;
use App\Models\Core\{Role, UserSocialNetwork};
use App\Models\Catalogs\SocialNetwork;
use Auth;

class LoginController extends Controller
{
    use GeneralResponse;
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), User::getValidationRules('login'));

        if ($validator->fails()) {
            return $this->genResponse(400, $validator->errors() );
        }

        $credentials = [
          'email_hash'  => md5($request->input('email')),
          'password'    => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->email_verified_at) {
                return $this->genResponse( 401, null, "Correo no verificado", 'attempt-login');
            }
            if (!$user->active) {
                //the user has been deactivated (perhaps by an admin)
                return $this->genResponse( 401, null, "Usuario no activado", 'attempt-login');
            }
            
            $data = [
                'token' => auth()->user()->createToken('KokonutApi')->accessToken,
                'role' => $user->role->key,
            ];

            return $this->genResponse( 200, $data, null, 'attempt-login');
        }
        else {
          return $this->genResponse(401);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();
        $accessToken->revoke();
        return $this->genResponse(200, null,'Logout Successfull','logout');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     */
    public function redirectToProvider($driver)
    {
        $validator = config('services.'. $driver );
        
        if( $validator ){
            return Socialite::driver($driver)->stateless()->redirect();
        }
        else {
            return $this->genResponse(400, 'No es aplicación valida para logearse' );
        }
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleProviderCallback(Request $request, $driver)
    {
        if ($request->get('error')){
            return $this->genResponse(400, 'Ocurrio un Error con el proveedor' );
        }
        $user_socialite = Socialite::driver($driver)->stateless()->user();

        $user_social_network =  UserSocialNetwork::where('user_social_network_id', $user_socialite->getId())->first();
        // check for duplicate social network
        if (!$user_social_network) {

            $email_hash = md5($user_socialite->getEmail());

            $user =  User::where('email_hash', $email_hash)->first();
            // check for duplicate emails
            if(!$user) {
                $role = Role::where('key','platform_user')->first();
                $now = new \DateTime;
                
                $user = User::create([
                    'nickname'          => $user_socialite->getName(),
                    'email'             => $user_socialite->getEmail(),
                    'email_hash'        => $email_hash,
                    'role_id'           => $role->id,
                    'active'            => 1, 
                    'status'            => 0,
                    'email_verified_at' => $now
                ]);

                $user->encrypt_id = encrypt($user->id);
                $user->save();
            }

            $social_network = SocialNetwork::where('name', $driver)->first();

            $user_social_network = UserSocialNetwork::create([
                'user_id'                   => $user->id,
                'user_social_network_id'    => $user_socialite->getId(),
                'social_network_id'         => $social_network->id,
                'social_network_avatar'     => $user_socialite->getAvatar(),
            ]);

            $user_social_network->encrypt_id = encrypt($user_social_network->id);
            $user_social_network->save();
        }

        auth()->login($user_social_network->user);

        $data = [
            'token' => auth()->user()->createToken('KokonutApi')->accessToken,
            'role' => $user_social_network->user->role->key,
        ];

        return $this->genResponse( 200, $data, null, 'attempt-login');
    }
}
