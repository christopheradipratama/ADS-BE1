<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class KaryawanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'nomor_induk' => $this->nomor_induk,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'tanggal_lahir' => $this->formattedDate($this->tanggal_lahir), //Melakukan 
            'tanggal_bergabung' => $this->formattedDate($this->tanggal_bergabung),
            'created_at' => $this->serializeDate(Carbon::parse($this->created_at)),
            'updated_at' => $this->serializeDate(Carbon::parse($this->updated_at)),
        ];
    }

    private function formattedDate($date): ?string
    {
        if (!is_null($date)) {
            if (is_string($date)) {
                $date = Carbon::createFromFormat('Y-m-d', $date); //Melakukan Formatting Date
            }
            return $date->format('d-M-y');
        }
        return null;
    }

    protected function serializeDate($date): ?string
    {
        if (!is_null($date)) {
            return $date->format('Y-m-d H:i:s');
        }
        return null;
    }
}