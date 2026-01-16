<?php

namespace App\Services;

use App\Models\Reunion;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ReunionService
{
    public function createReunion(array $data, ?UploadedFile $file = null): Reunion
    {
        if ($file) {
            $data['archivo_acta'] = $file->store('actas', 'public');
        }

        return Reunion::create($data);
    }

    public function updateReunion(Reunion $reunion, array $data, ?UploadedFile $file = null): Reunion
    {
        if ($file) {
            if ($reunion->archivo_acta) {
                Storage::disk('public')->delete($reunion->archivo_acta);
            }
            $data['archivo_acta'] = $file->store('actas', 'public');
        }

        $reunion->update($data);

        return $reunion;
    }

    public function deleteReunion(Reunion $reunion): void
    {
        if ($reunion->archivo_acta) {
            Storage::disk('public')->delete($reunion->archivo_acta);
        }

        $reunion->delete();
    }
}
