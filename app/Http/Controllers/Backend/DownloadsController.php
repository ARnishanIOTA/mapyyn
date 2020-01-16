<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ClientsExport;
use App\Exports\PaymentsExport;
use App\Exports\ProvidersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function providers()
    {
        return Excel::download(new ProvidersExport, 'providers.xlsx');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payments()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
   
}
