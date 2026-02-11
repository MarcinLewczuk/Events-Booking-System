<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'in:Mr,Mrs,Miss,Ms,Dr,Prof'],
            'first_name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'contact_telephone_number' => ['required', 'string', 'max:20'],
            'contact_address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'contact_telephone_number' => $request->contact_telephone_number,
            'contact_address' => $request->contact_address,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'buyer_approved_status' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to tag selection page after registration
        return redirect()->route('profile.tags')->with('success', 'Welcome! Please select your interests to get personalized recommendations.');
    }
}