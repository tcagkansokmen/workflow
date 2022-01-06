<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Core\ContractSign;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use App\Models\Firm;
use App\Models\FirmOrder;
use App\Models\FirmModule;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Notifications\NewTrialUser;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Str;
use Mail;
class AuthController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        return View::make('auth.login');
    }

    public function register(): \Illuminate\Contracts\View\View
    {
        return View::make('auth.register');
    }

    public function save(): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $credentials = $this->request->only(['email', 'password']);
        $remember = $this->request->has('remember');
        $results = array('status' => 0, "message" => "Giriş başarısız, lütfen tekrar deneyin.");
        if (Auth::attempt($credentials, $remember)) {
            $results = array('status' => 1, "message" => "Giriş başarılı, yönlendiriliyorsunuz.", "redirect" => "/");
        }
        return response()->json($results);
    }
    
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return redirect('/')->with('info', 'Session closed');
    }


    public function getForgetPassword()
    {
        return view('auth.forgot-password');
    }

    public function postForgetPassword(): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        if (!$this->request->has('email')) {
            $results = array('status' => 0, "message" => "Lütfen geçerli bir e-posta adresiyle tekrar deneyin.");
            return response()->json($results);
        }
        $user = User::where('email', $this->request->input('email'))->first();
        if ($user === null) {
            $results = array('status' => 0, "message" => "Lütfen geçerli bir e-posta adresiyle tekrar deneyin.");
            return response()->json($results);
        }

        $token = Str::random(20);
        $hashed = Hash::make($token);
        $compose_to = $user->email;

        // delete all previus tokens
        DB::table('password_resets')->where('email', $user->email)->delete();

        // save the last one.
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $hashed,
            'created_at' => now(),
        ]);

        $title = "Şifre Sıfırlama Talebiniz";

        $message = "<p>Merhaba,</p><p>Şifre sıfırlama talebiniz için aşağıdaki linke tıklayabilirsiniz. Eğer bu talebi siz oluşturmadıysanız, dikkate almayabilirsiniz.</p>";

        $message .= '<p><a href="'.__('messages.panel_url').'/reset-password/'.$user->email.'/'.$token.'">İşlemi Tamamlamak için Tıklayınız</a></p>';
        $subject = "Şifre Sıfırlama Talebiniz";

        $ddd = array('name'=>$subject, 'body' => $message);

          Mail::send('emails.mail', $ddd, function($message) use ($compose_to, $title, $subject) {
          $message->to(explode(',', $compose_to))
          ->subject($subject);
          $message->from(env('MAIL_USERNAME'), __('messages.title'));
          });

        $results = array('status' => 1, "message" => "Sıfırlama talebiniz alınmıştır. Lütfen adımları e-posta adresinizden devam ettirin.", "redirect" => "/");

        return response()->json($results);
    }

    public function getResetPassword(string $email, string $token): \Illuminate\Contracts\View\View
    {
        $user = User::where('email', $email)->first();
        if ($user === null) {
            return View::make('auth.forget', [
                'alert' => 'No user found',
                'email' => $email, 'token' => $token
            ]);
        }
        if (!$user->checkPasswordResetToken($token)) {
            return View::make('auth.forget', [
                'alert' => 'Wrong token',
                'email' => $email, 'token' => $token
            ]);
        }
        return View::make('auth.forget', ['email' => $email, 'token' => $token]);
    }

    public function postResetPassword(): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required',
            'password_repeat' => 'required|same:password'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();
        if ($user === null) {
            $result = array(
                'status' => 0,
                'message' => "Lütfen eposta adresinize gelen linki kullanınız."
            );
            return response()->json($result);
        }
        if (!$user->checkPasswordResetToken($this->request->input('token'))) {
            $result = array(
                'status' => 0,
                'message' => "Lütfen eposta adresinize gelen linki kullanınız."
            );
            return response()->json($result);
        }
        $user->updatePassword($this->request->input('password'));
        $result = array(
            'status' => 1,
            'redirect' => '/',
            'message' => "Parolanız başarıyla güncellendi. Giriş yapabilirsiniz."
        );
        return response()->json($result);
    }

}
