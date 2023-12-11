<?php

namespace App\Http\Controllers;

use App\Mail\AuthMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    function index()
    {
        return view('halaman_auth/login');
    }
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'password harus diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($infologin)){
            if(Auth::user()->email_verified_at !=null){
                if(Auth::user()->role === 'admin'){
                    return redirect()->route('admin')->with('success','Halo admin, anda berhasil login');
                }else if(Auth::user()->role === 'user'){
                    return redirect()->route('user')->with('success','Berhasil login');
                }
            }else{
                Auth::logout();
                return redirect()->route('auth')->withErrors('akun anda belum aktif, harap verifikasi terlebih dahulu');
            }
            return "Login Success"; 
        } else {
            return redirect()->route('auth')->withErrors('Email atau password salah');
        }
    }
    function create()
    {
        return view('halaman_auth/register');
    }
    function register(Request $request)
    {
        $str = Str::random(100);

        $request->validate([
            'fullname' => 'required|min:5',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'gambar' => 'required|image|file',
        ],[
            'fullname.required' => 'Full Name wajib diisi',
            'fullname.min' => 'Full Name minimal 5 karakter',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email telah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'gambar.required' => 'Gambar wajib diupload',
            'gambar.image' => 'Gambar yang diupload harus image',
            'gambar.file' => 'Gambar harus berupa file',

        ]);

        $gambar_file = $request->file('gambar');
        $gambar_ekstensi = $gambar_file->extension();
        $nama_gambar = date('ymdhis') . "." . $gambar_ekstensi;
        $gambar_file->move(public_path('picture/accounts'),$nama_gambar);

        $inforegister = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $request->password,
            'gambar' => $nama_gambar,
            'verify_key' => $str
        ];

        User::create($inforegister);

        $details = [
            'nama'=> $inforegister['fullname'], 
            'role'=> 'user',
            'datetime' => date('Y-m-d H:i:s'),
            'website'=> 'project-final',
            'url'=>'http://'. request()->getHttpHost(). "/" ."verify/".$inforegister['verify_key'],
        ]; 

        Mail::to($inforegister['email'])->send(new AuthMail($details));

        return redirect()->route('auth')->with('success','link verifikasi telah dikirim ke email anda. cek email untuk melakukan verifikasi');
    }
    function verify($verify_key){
        $keyCheck = User::select('verify_key')
        ->where('verify_key', $verify_key)
        ->exists();

        if($keyCheck){
            $user = User::where('verify_key', $verify_key)->update(['email_verified_at' => date('Y-m-d H:i:s')]);

            return redirect()->route('auth')->with('success', 'Verifikasi berhasil Akun anda sudah aktif.');
        }else {
            return redirect()->route('auth')->withErrors('Keys tidak valid, pastikan telah melakukan register')->withInput();
        }
    }
    function logout(){
        Auth::logout();
        return redirect('/');
    }
}
