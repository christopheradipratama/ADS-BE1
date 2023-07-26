<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\KaryawanResource;
use App\Http\Resources\RestCutiResource;
use App\Http\Resources\CutiResource;
use App\Models\Karyawan;
use App\Models\Cuti;
use Carbon\Carbon;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuti = Cuti::all();

        if ($cuti) {
            return response(
                [
                    'message' => 'Successfully get data Cuti',
                    'data' => CutiResource::collection($cuti), //Penerapan Eloquent API Resource
                ],
                200,
            );
        }

        return response(
            [
                'message' => 'Failed get data Cuti',
                'data' => null,
            ],
            400,
        );
    }

    public function index_ever_cuti()
    {
        $karyawan = Karyawan::whereHas('cuti', function ($query) {
            $query->where('tanggal_cuti', '<', Carbon::today());
        })->get();

        return response(
            [
                'message' => 'Daftar karyawan yang pernah mengambil cuti',
                'data' => KaryawanResource::collection($karyawan), //Penerapan Eloquent API Resource
            ],
            200,
        );
    }

    public function index_rest_cuti(Request $request)
    {
        $tahun = $request->input('tahun'); // Mengambil tahun dari request parameter

        $currentYear = $tahun ?? date('Y'); //Jika tidak ada inputan, maka akan menggunakan tahun ini

        $karyawans = Karyawan::select('karyawans.nomor_induk', 'karyawans.nama', 'karyawans.created_at')
            ->selectRaw('(12 - COALESCE(SUM(cutis.lama_cuti), 0)) AS sisa_cuti')
            ->leftJoin('cutis', function ($join) use ($currentYear) {
                $join->on('karyawans.nomor_induk', '=', 'cutis.nomor_induk')->whereYear('cutis.tanggal_cuti', $currentYear);
            })
            ->groupBy('karyawans.nomor_induk', 'karyawans.nama', 'karyawans.created_at')
            ->get();

        $karyawans->transform(function ($karyawan) {
            $karyawan->sisa_cuti = max(0, $karyawan->sisa_cuti);
            return $karyawan;
        });

        return response(
            [
                'message' => 'Sisa cuti setiap karyawan (quota cuti 12/tahun)',
                'data' => RestCutiResource::collection($karyawans),
            ],
            200,
        );
    }
}