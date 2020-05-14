<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function contrato($pasta, $file)
    {

        if (! file_exists(env('APP_PATH')."storage/app/contrato/".$pasta."/".$file)) {

            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('contrato/'.$pasta.'/'.$file);
    }

    public function anexoscomunica($file)
    {

        if (! file_exists(env('APP_PATH')."storage/app/comunica/anexos/".$file)) {

            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('comunica/anexos/'.$file);
    }

    public function anexosocorrencia($path,$file)
    {
        if (! file_exists(env('APP_PATH')."storage/app/ocorrencia/".$path."/".$file)) {

            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('ocorrencia/'.$path."/".$file);
    }

    public function declaration($file)
    {
        if (! file_exists(env('APP_PATH')."storage/app/declaration/".$file)) {
            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('declaration/'.$file);
    }

    public function importacao($path,$file)
    {
        if (! file_exists(env('APP_PATH')."storage/app/importacao/".$path."/".$file)) {

            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('importacao/'.$path."/".$file);
    }
}
