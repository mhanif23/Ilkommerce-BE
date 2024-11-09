<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\GenerateAkunMail;
use Illuminate\Http\Request;

use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Support\Str;

use App\Models\User;

use App\Utilities\ApiNoReply;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules =  [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
            }

            $password = Str::random(15);  // Generate random password
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($password);
            $user->toko_id = null;
            $user->save();

            $mailData = [
                'title' => 'Pemberitahuan Akun Tokped',
                'body' => '
                    <p>Selamat datang di Tokped!</p>

                    <p>Anda telah didaftarkan sebagai pengguna di sistem kami. Berikut adalah informasi akun Anda:</p>
                    
                    <ul>
                        <li><strong>Nama Pengguna:</strong> ' . $user->name . '</li>
                        <li><strong>Email:</strong> ' . $user->email . '</li>
                    </ul>
                    
                    <p>Password Akun Anda: <strong>' . $password . '</strong></p>
                    
                    <p>Silakan simpan informasi ini dengan aman. Gunakan email dan password di atas untuk login ke sistem kami. Kami merekomendasikan Anda untuk segera mengganti password Anda setelah masuk pertama kali.</p>
                    
                    <p>Salam,<br>Tim Tokped</p>
                '
            ];

            Mail::to($user->email)->send(new GenerateAkunMail($mailData));

            DB::commit();
            
            return $this->api_response_success('User berhasil didaftarkan. Silakan Check Email untuk Aktivasi Akun.', [
                'name' => $user->name,
                'email' => $user->email,
            ]);

            
        } catch (\Throwable $th) {
            DB::rollback();  // Rollback jika terjadi exception
            return $this->api_response_error($th->getMessage() . " - " . $th->getLine(), [], $th->getTrace());
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails())
                return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());

            if (Auth::attempt($request->only(['email', 'password']))) {
                $user = User::where('email', $request->email)->first();

                $response_data = User::generateUserToken($user);

                if (isset($response_data['status']))
                    return $this->api_response_error($response_data['error']);

                $response_data['toko_id'] = $user->toko_id;

                return $this->api_response_success('Login berhasil.', $response_data);
            } else {
                return $this->api_response_error('Email atau password salah', [], ['Email atau password salah']);
            }
        } catch(\Throwable $th){
            return $this->api_response_error($th->getMessage()." - ".$th->getLine(), [], $th->getTrace());
        }
    }

    public function getUserInfo()
    {
        $data = PersonalAccessToken::where('token', request()->user()->currentAccessToken()->token)->first();
        if ($data) {
            $user_info = User::where('id', $data->tokenable_id)->first();
            $user_info->toko_id = $user_info->toko_id;

            return $this->api_response_success('success', $user_info->toArray());
        } else {
            return $this->api_response_error('Token Tidak Ditemukan', []);
        }
    }

    public function revokeToken(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        setActivityLog(
            "User Melakukan Logout",
            null,
            "AUTH LOGOUT"
        );

        return $this->api_response_success('User token revoked', []);
    }

    public function noAuth()
    {
        return $this->api_response_error('Unauthorized Access', [], [], 401);
    }

    public function checkAuth()
    {
        $user = auth()->user();
        $auth_token_info = User::getAuthTokenInfo();
        $response_data = User::generateUserToken($user);

        if (!$auth_token_info) {
            return $this->api_response_error('Invalid Token', [], [], 403);
        }

        $combined_data = array_merge($auth_token_info, $response_data);

        $combined_data['toko_id'] = $user->toko_id;

        return $this->api_response_success('OK', $combined_data);
    }

    // public function accountActivation(Request $request){
    //     DB::beginTransaction();
    //     try{

    //         $validator = Validator::make($request->all(),[
    //             'password' => 'required|min:12|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
    //             'verification_code' => 'required|exists:users',
    //         ]);

    //         if ($validator->fails()){
    //             return $this->api_response_error('Periksa data yang anda isi!', [], $validator->errors()->toArray());
    //         }

    //         $user = User::where('verification_code', $request->verification_code)->whereNotNull('verification_code')->first();

    //         if (!$user)
    //             return $this->api_response_error('Data tidak ditemukan!');
    //         else if (!empty($user->email_verified_at))
    //             return $this->api_response_error('Token tidak valid!');

    //         $user->password = bcrypt($request->password);
    //         $user->is_validated = true;
    //         $user->email_verified_at = now();
    //         $user->update();

    //         DB::commit();
    //         return $this->api_response_success('Akun berhasil diaktivasi, silakan untuk login.');
    //     } catch(\Exception $e) {
    //         DB::rollback();
    //         return $this->api_response_error($e->getMessage());
    //     }
    // }

    public function checkMailForgotPassword(Request $request){
        DB::beginTransaction();
        try{

            $validator = Validator::make($request->all(),[
                'email' => 'required|email|exists:users',
            ]);

            if ($validator->fails()){
                return $this->api_response_error('Periksa data yang anda isi!', [], $validator->errors()->toArray());
            }

            DB::table('password_resets')->where('email', $request->email)->delete();

            $user = User::where('email', $request->email)->first();

            $token = uniqid(Str::random(16));
            $password_reset = DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);


            $content = '
            <center>
                <div style="padding-bottom: 30px; padding-top: 30px; font-size: 17px;">
                    <strong>Tokped - EBTKE</strong>
                </div>
            </center>

            <div style="padding-bottom: 30px">
                Halo, '.$user->name.'
            </div>
            <div style="padding-bottom: 30px">
                Anda menerima email ini karena kami menerima permintaan setel ulang sandi untuk akun Anda.
            </div>
            <div style="padding-bottom: 40px; text-align:center;">
                <a href="'. env('APP_FE_URL') . '/#/change-password/' .$token. '" target="_blank" rel="noopener noreferrer" class="myButton" style=" background: linear-gradient(to bottom, #fff350 5%, #fff241 100%);
                background-color: #77b55a;
                border-radius: 4px;
                display: inline-block;
                cursor: pointer;
                color: #000000;
                font-family: Arial;
                font-size: 14px;
                font-weight: bold;
                padding: 14px 28px;
                text-decoration: none;">Setel Ulang Password</a>
            </div>
            <div style="padding-bottom: 30px">
                Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak meminta pengaturan ulang kata sandi, tidak ada tindakan lebih lanjut yang diperlukan.
            </div>
            <div style="padding-bottom: 30px">
                Seluruh informasi dan data pribadi yang disampaikan akan dijamin kerahasiaannya.
                Info lebih lanjut bisa di lihat di Website Resmi Tokped - EBTKE.
            </div>
            ';
            $apiNoReply = new ApiNoReply;
            $resultapiNoReply = $apiNoReply->sendMail($user->email, 'Konfirmasi Permintaan Setel Ulang Sandi Akun Tokped - EBTKE', $content);

            DB::commit();
            return $this->api_response_success('Permintaan berhasil dikirim, Silahkan periksa email untuk ubah password.');
        } catch(\Exception $e) {
            DB::rollback();
            return $this->api_response_error($e->getMessage());
        }
    }

    public function checkTokenForgotPassword(Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'token' => 'required|exists:password_resets',
            ]);

            if ($validator->fails()){
                return $this->api_response_error('Periksa data yang anda isi!', [], $validator->errors()->toArray());
            }

            $passwordReset = DB::table('password_resets')->where('token', $request->token)->first();

            if ($passwordReset->created_at < now()->subHour()) {
                $passwordReset->delete();
                return $this->api_response_error('Token Expired!', [], [
                    'token' => ['Token yang anda masukkan sudah Expired!']
                ]);
            }
            
            return $this->api_response_success('Token Valid!');
        } catch(\Exception $e) {
            return $this->api_response_error($e->getMessage());
        }
    }

    public function updatePassword(Request $request){
        DB::beginTransaction();
        try{

            $validator = Validator::make($request->all(),[
                'token' => 'required|exists:password_resets',
                'password' => 'required|min:12|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ]);

            if ($validator->fails()){
                return $this->api_response_error('Periksa data yang anda isi!', [], $validator->errors()->toArray());
            }

            $passwordReset = DB::table('password_resets')->where('token', $request->token);
            $getTokenData = $passwordReset->first();
            if ($getTokenData->created_at > now()->addHour()) {
                $passwordReset->delete();
                return $this->api_response_error('Token Expired!', [], [
                    'token' => ['Token yang anda masukkan sudah Expired!']
                ]);
            }

            $user = User::where('email', $getTokenData->email)->update([
                'password' => bcrypt($request->password),
            ]);

            $passwordReset->delete();

            DB::commit();
            return $this->api_response_success('Password berhasil diubah!');
        } catch(\Exception $e) {
            DB::rollback();
            return $this->api_response_error($e->getMessage());
        }
    }
}
