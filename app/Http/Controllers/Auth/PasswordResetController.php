<?php



namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;

use App\Models\Manager;

use App\Services\RecaptchaService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;



class PasswordResetController extends Controller

{

    public function __construct(private RecaptchaService $recaptcha) {}



    public function showForgot()

    {

        return view('auth.forgot-password', [

            'recaptchaSiteKey' => config('resmenu.recaptcha_site_key'),

        ]);

    }



    public function sendReset(Request $request)

    {

        if (! $this->recaptcha->verifyRequest($request)) {

            return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);

        }



        $request->validate(['email' => 'required|email']);

        $manager = Manager::where('email', $request->email)->first();

        if ($manager) {

            $token = Str::random(64);

            Cache::put('pwd_reset:'.$token, $manager->id, now()->addHour());

        }



        return back()->with('success', 'If that email exists, we sent reset instructions.');

    }



    public function showReset(string $token)

    {

        if (! Cache::has('pwd_reset:'.$token)) {

            abort(404);

        }



        return view('auth.reset-password', ['token' => $token]);

    }



    public function reset(Request $request)

    {

        $data = $request->validate([

            'token' => 'required|string',

            'password' => 'required|string|min:'.config('resmenu.password_min_length', 8).'|confirmed',

        ]);



        $managerId = Cache::pull('pwd_reset:'.$data['token']);

        if (! $managerId) {

            return back()->withErrors(['password' => 'Reset link expired.']);

        }



        $manager = Manager::findOrFail($managerId);

        $manager->password_hash = Hash::make($data['password']);

        $manager->save();



        return redirect()->route('login')->with('success', 'Password updated. You can sign in.');

    }

}


