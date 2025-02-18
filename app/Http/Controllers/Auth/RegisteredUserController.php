<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Support\Facades\Http;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $accountTypes = \App\Models\AccountType::where('is_active', 1)->get();
        return view('auth.register', compact('accountTypes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required'],
            'g-recaptcha-response' => 'required',
        ]);

        $recaptcha = $request->input('g-recaptcha-response');

        $url = "https://www.google.com/recaptcha/api/siteverify";

        $params = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha,
            'remoteip' => IpUtils::anonymize($request->ip())
        ];

        // Make the HTTP request
        $response = Http::asForm()->post($url, $params);

        // Decode response
        $result = $response->json(); // Instead of json_decode($response)

        // Check if reCAPTCHA verification failed
        if (!($response->successful() && ($result['success'] ?? false) == true)) {
            $request->session()->flash('message', "Please complete the reCAPTCHA again to proceed.");
            return redirect()->back();
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type_id' => $request->account_type,
            'user_type' => 'Member',
            'dark_mode' => 1,
        ]);

        $notification = new \App\Models\MemberReminder;
        $notification->title = "A new user has signed up to IPPU";
        $notification->member_id = $user->id;
        $notification->reminder_date = date('Y-m-d');
        $notification->status = "Unread";
        $notification->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
