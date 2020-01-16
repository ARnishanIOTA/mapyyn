<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClientsExport implements FromView
{
    public function view(): View
    {
        return view('exports.clients', [
            'clients' => Client::all()
        ]);
    }
}
