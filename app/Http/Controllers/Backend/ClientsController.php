<?php

namespace App\Http\Controllers\Backend;

use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.clients.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
      
        $clients = Client::query();
        $url = url('/backend/clients/');
        return Datatables::of($clients)
            ->addColumn('action', function ($client) use($url) {
                return '
                <a href="" data-id="'.$client->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">
                <span><i class="flaticon-delete-2"></i><span> '.trans('lang.remove').' </span></span></a>';
            })
            ->editColumn('first_name', function ($client){
                if($client->image == null){
                    return '<a href='.asset("uploads/default.png").' data-lity><img src='.asset("uploads/default.png").' style="border-radius:50%" width="40" hieght="50"></a>
                    '.' '. $client->first_name . ' ' . $client->last_name;
                }else{
                    return '<a href='.asset("uploads/$client->image").' data-lity><img src='.asset("uploads/$client->image").' style="border-radius:50%" width="40" hieght="50"></a>
                    '.' '. $client->first_name . ' ' . $client->last_name;
                }
            })
            ->editColumn('is_active', function ($client) use($url){
                if($client->is_active == 0){
                    return '<a href="'.$url.'/'.$client->id.'" class="btn btn-warning m-btn m-btn--icon">'.trans('lang.nonactive').'</a>';
                }else{
                    return '<a href="'.$url.'/'.$client->id.'" class="btn btn-success m-btn m-btn--icon">'.trans('lang.active').'</a>';
                }
            })
            ->editColumn('phone', function ($client) use($url){
                return $client->code.$client->phone;
            })
            ->rawColumns(['action', 'is_active', 'first_name'])
            ->make(true);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if($client->is_active == 1){
            $client->is_active = 0;
            $client->save();
        }else{
            $client->is_active = 1;
            $client->save();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
    }
}
