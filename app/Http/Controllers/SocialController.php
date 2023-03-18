<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback($provider)
    {
        
        try { // we are using this to avoid error when cancel fron login with social 
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $th) {
            return redirect(route('login'));
        }


        // check if the user already exist
        $user = User::where('email', $socialUser->getEmail())->first();
        // if user doesn't exist
        // create user
        if (!$user) {
            $name = $socialUser->getNickname() ?? $socialUser->getName();
            $user = User::create([
                'name' => $name,
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(7))
            ]);
            // create social for user 
            $user->socials()->create([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken
            ]);
        }
        // if user does exist
        $socials = Social::where('provider', $provider)
            ->where('user_id', $user->id)->first();
        // check if user doesn't have socials
        if (!$socials) {
            // add socials to user
            $user->socials->create([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken
            ]);
        }
        // login user 
        auth()->login($user);
        // redirect to th app
        return redirect('/dashboard');
    }
}
