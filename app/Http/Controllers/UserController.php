<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])/|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json(['message' => 'User created'], 200);
        // return response($response, 201);
    }

    public function getUserByToken(Request $request)
    {
        $token = $request->input('token');
        $user = User::where('api_token', $token)->first();

        if ($user) {
            // Utente trovato
            return response()->json($user);
        } else {
            // Token non valido o utente non trovato
            return response()->json(['error' => 'Utente non trovato'], 404);
        }
    }

    public function logout(Request $request)
    {
        //il token va eliminato con il logout
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }


    public function login(Request $request)
{
    $fields = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string'
    ]);

    // Estraggo l'utente con l'email inserita
    $user = User::where('email', $fields['email'])->first();

    // Controllo se l'utente esiste e poi se la password inserita e quella nel database corrispondono
    if ($user && Hash::check($fields['password'], $user->password)) {
        // Verifico se l'utente vuole essere ricordato e imposto la scadenza del token di conseguenza
        $remember = $request->input('remember', false); // Se non viene fornito, assume il valore di default false
        $token = $user->createToken('myapptoken', ['remember' => $remember])->plainTextToken;
        Auth::login($user, $remember);

        // Aggiungo il valore di "remember" all'array di risposta
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'logged in',
            'remember' => $remember,
        ];

        return response($response);
    } else {
        return response([
            'message' => 'Wrong credentials'
        ], 401);
    }
}

}
