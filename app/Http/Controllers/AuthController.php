<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Requests\JoinRequest;
use App\User;
use App\Client;

class AuthController extends Controller
{
    public function join(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:8|max:50|string|unique:users,username',
            'password' => 'required|min:8|max:50|string|alpha_num',
            'uuid' => 'required|exists:clients,uuid'
        ]);

        $uuid = $request->input('uuid');
        $api_key = app('hash')->make(Carbon::now());
        
        try {
            \DB::beginTransaction();

            $user = new User();
            $user->username = $request->input('username');
            $user->password = app('hash')->make($request->input('password'));
            $user->api_key = $api_key;
            $user->save();

            $client = Client::firstOrCreate(['uuid' => $uuid]);
            $client->user()->associate($user);
            $client->save();

            \DB::commit();

            return response(['uuid' => $uuid, 'api_key' => $api_key]);
        } catch (Exception $e) {
            \DB::rollback();
            return response(['error' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $user = User::with(['client'])->where('username', $request->input('username'))->first();
        app('hash')->check($request->input('password'), $user->password);

        $api_key = app('hash')->make(Carbon::now());
        $user->update([
            'api_key' => $api_key, 
            'last_login' => Carbon::now()
        ]);

        return response(['uuid' => $user->client->uuid, 'api_key' => $api_key]);
    }
}
