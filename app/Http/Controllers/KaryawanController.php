<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\KaryawanResource;
use App\Http\Resources\CutiResource;
use App\Models\Karyawan;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawan = Karyawan::all();

        if ($karyawan->isEmpty()) {
            return response(
                [
                    'message' => 'Failed to get data Karyawan',
                    'data' => null,
                ],
                404,
            );
        }

        return response(
            [
                'message' => 'Successfully get data Karyawan',
                'data' => KaryawanResource::collection($karyawan), //Penerapan Eloquent API Resource
            ],
            200,
        );
    }

    public function generateNomorInduk()
    {
        $lastKaryawan = Karyawan::orderByDesc('nomor_induk')->first();
        $lastId = $lastKaryawan ? (int) substr($lastKaryawan->nomor_induk, 5) : null;
        $nextId = $lastId + 1;
        $nextIdFormatted = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return 'IP06' . $nextIdFormatted;
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date|before_or_equal:' . Carbon::now()->toDateString(), //Validasi Tanggal Harus Sebelum Hari Peginputan
            'tanggal_bergabung' => 'required|date|before_or_equal:' . Carbon::now()->toDateString(), //Validasi Tanggal Harus Sebelum Hari Peginputan
        ]);

        $nomorInduk = $this->generateNomorInduk();

        $karyawan = Karyawan::create([
            'nomor_induk' => $nomorInduk,
            'nama' => $request->input('nama'),
            'alamat' => $request->input('alamat'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'tanggal_bergabung' => $request->input('tanggal_bergabung'),
        ]);

        if ($karyawan) {
            unset($karyawan->id);
            return response(
                [
                    'message' => 'Successfully to create Karyawan',
                    'data' => new KaryawanResource($karyawan), //Penerapan Eloquent API Resource
                ],
                201,
            );
        }

        return response(
            [
                'message' => 'Failed to create Karyawan',
                'data' => null,
            ],
            400,
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nomor_induk)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|before_or_equal:' . Carbon::now()->toDateString(),
            'tanggal_bergabung' => 'required|before_or_equal:' . Carbon::now()->toDateString(),
        ]);

        $karyawan = Karyawan::where('nomor_induk', $nomor_induk)->first();

        if ($karyawan === null) {
            return response(
                [
                    'message' => 'Failed to get data Karyawan',
                    'data' => null,
                ],
                404,
            );
        }

        $karyawan->nama = $request->input('nama');
        $karyawan->alamat = $request->input('alamat');
        $karyawan->tanggal_lahir = $request->input('tanggal_lahir');
        $karyawan->tanggal_bergabung = $request->input('tanggal_bergabung');

        $updated = $karyawan->save();

        if ($updated) {
            return response(
                [
                    'message' => 'Succesfully to update Karyawan',
                    'data' => new KaryawanResource($karyawan), //Penerapan Eloquent API Resource
                ],
                200,
            );
        }

        return response(
            [
                'message' => 'Failed to update Karyawan',
                'data' => null,
            ],
            400,
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nomor_induk)
    {
        $karyawan = Karyawan::where('nomor_induk', $nomor_induk)->first();

        // Check if the Karyawan exists
        if (!$karyawan) {
            return response(
                [
                    'message' => 'Failed to get data Karyawan',
                    'data' => null,
                ],
                404,
            );
        }

        // Delete the Karyawan
        $deleted = $karyawan->delete();

        if ($deleted) {
            return response(
                [
                    'message' => 'Succesfully to delete Karyawan',
                    'data' => new KaryawanResource($karyawan), //Penerapan Eloquent API Resource
                ],
                200,
            );
        }

        return response(
            [
                'message' => 'Failed to delete Karyawan',
                'data' => null,
            ],
            400,
        );
    }

    public function index_oldest_karyawan()
    {
        $karyawan = Karyawan::orderBy('tanggal_bergabung')
            ->limit(3)
            ->get();

        return response(
            [
                'message' => 'Data 3 Karyawan yang pertama kali bergabung',
                'data' => KaryawanResource::collection($karyawan), //Penerapan Eloquent API Resource
            ],
            200,
        );
    }
}