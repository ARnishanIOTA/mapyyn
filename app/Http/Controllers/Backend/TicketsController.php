<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketComment;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TicketRequest;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.tickets.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $tickets = Ticket::with('provider', 'client')->where('type', 'admin');
        $url = url('/backend/tickets/');
        return Datatables::of($tickets)
            ->addColumn('action', function ($ticket) use($url) {
                return '<a href="'.$url.'/'.$ticket->id.'" class="btn btn-success m-btn m-btn--icon">
                <span><i class="fa fa-eye"></i><span>View</span></span></a>';
            })
            ->editColumn('status', function ($ticket) use($url) {
                if($ticket->status == 'opened'){
                    return '<label class="m-badge m-badge--default m-badge--wide">Opened</label>';
                }else{
                    return '<label class="m-badge m-badge--success m-badge--wide">Closed</label>';
                }
            })
            ->editColumn('client', function ($ticket) use($url) {
                if($ticket->client_id != null){
                    return $ticket->client->first_name . ' ' . $ticket->client->last_name;
                }else{
                    return '--';
                }
            })
            ->editColumn('provider', function ($ticket) use($url) {
                if($ticket->provider_id != null){
                    return $ticket->provider->first_name . ' ' . $ticket->provider->last_name;
                }else{
                    return '--';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $ticket = $ticket->load(['comments' => function($query){
            $query->orderBy('id', 'DESC');
        }, 'comments'])->where('type', 'admin')->first();
        return view('ticket', compact('ticket'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {

        $request->validate([
            'comment' => 'required|string',
            'status' => [Rule::in(['opened', 'closed'])]
        ]);

        if($ticket->status == 'closed'){
            return back();
        }
            $inputs = ['ticket_id' => $ticket->id,'comment' => $request->comment];
            $inputs['user_id'] = auth()->id();

            TicketComment::create($inputs);
            if($request->status != $ticket->status){
                $ticket->status = $request->status;
                $ticket->save();
            }
            
        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Ticket $ticket)
    // {
    //     $file = public_path("uploads/$ticket->image");
    //     if(file_exists($file)){
    //         unlink($file);
    //     } 
    //     $ticket->delete();
    // }
}
