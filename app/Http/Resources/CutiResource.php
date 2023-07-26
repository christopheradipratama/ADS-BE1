<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CutiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'nomor_induk' => $this->nomor_induk,
            'tanggal_cuti' => $this->formattedDate($this->tanggal_cuti), //Formatting Date
            'lama_cuti' => $this->lama_cuti,
            'keterangan' => $this->keterangan,
            'created_at' => $this->serializeDate(Carbon::parse($this->created_at)), //Serialization Date
            'updated_at' => $this->serializeDate(Carbon::parse($this->updated_at)), //Serialization Date 
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
            return $date->format('Y-m-d H:i:s'); //Melakukan Serialization Date
        }
        return null;
    }
}