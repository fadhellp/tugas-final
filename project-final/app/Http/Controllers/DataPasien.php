<?php

namespace App\Http\Controllers;

use App\Models\DataPasien as ModelsDataPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DataPasien extends Controller
{
    function index(){
        $data = ModelsDataPasien::all();
        return view('data_pasien.index',['data' => $data]);
    }
    function tambah()
    {
        return view('data_pasien.tambah');
    }
    function edit($id)
    {
        $data = ModelsDataPasien::find($id);
        return view('data_pasien.edit',['data' => $data]);
    }
    function hapus(Request $request)
    {
        ModelsDataPasien::where('id',$request->id)->delete();

        Session::flash('success','Berhasil hapus data');

        return redirect('/datapasien');
    }
    // new
    function create(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'umur' => 'required',
            'penyakit' => 'required',
        ], [
            'nama.required' => 'Nama Wajib Di isi',
            'alamat.required' => 'alamat Wajib Di isi',
            'jenis_kelamin.required' => ' masukkan jenis kelamin anda',
            'umur.required' => 'masukkan umur anda',
            'penyakit.required' => 'penyakit Wajib Di isi',
        ]);

        ModelsDataPasien::insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'penyakit' => $request->penyakit,
        ]);

        Session::flash('success', 'Data berhasil ditambahkan');

        return redirect('/datapasien')->with('success', 'Berhasil Menambahkan Data');
    }
    function change(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'umur' => 'required',
            'penyakit' => 'required',
        ], [
            'nama.required' => 'Nama Wajib Di isi',
            'nama.min' => 'Bidang nama minimal harus 3 karakter.',
            'alamat.required' => 'alamat Wajib Di isi',
            // 'email.email' => 'Format Email Invalid',
            'jenis_kelamin.required' => ' masukkan jenis kelamin anda',
            // 'nim.max' => 'NIM max 8 Digit',
            'umur.required' => 'masukkan umur anda',
            // 'angkatan.min' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            // 'angkatan.max' => 'Masukan 2 angka Akhir dari Tahun misal: 2022 (22)',
            'penyakit.required' => 'penyakit Wajib Di isi',
        ]);

        $datapasien = ModelsDataPasien::find($request->id);

        $datapasien->nama = $request->nama;
        $datapasien->alamat = $request->alamat;
        $datapasien->jenis_kelamin = $request->jenis_kelamin;
        $datapasien->umur = $request->umur;
        $datapasien->penyakit = $request->penyakit;
        $datapasien->save();

        Session::flash('success', 'Berhasil Mengubah Data');

        return redirect('/datapasien');
    }
}
