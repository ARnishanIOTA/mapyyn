<?php

namespace App\Http\Controllers\Backend;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.chat.index');
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $chat = Chat::with(['client' => function($query){
                    $query->select('id', 'first_name', 'last_name');
                }])->where('type', 1);
        $url = url('/backend/chats');
        return DataTables::of($chat)
            ->addColumn('action', function ($cha) use($url) {
                if($cha->offer_type == 1){
                    return '
                    <a href="'.$url . '/offers/' .$cha->id.'" class="btn btn-success m-btn m-btn--icon">
                    <span>'.trans('lang.offers').' </span></span></a>
                    ';
                }else{
                    return '
                    <a href="'.$url . '/request_offers/' .$cha->id.'" class="btn btn-success m-btn m-btn--icon">
                    <span>'.trans('lang.request_offers').' </span></span></a>';
                }
            })
            ->editColumn('offer_type', function ($cha) use($url) {
                if($cha->offer_type == 1){
                    return trans('lang.offer') . ' : ' . $cha->offer_id;
                }else{
                    return trans('lang.request_offer') . ' : ' . $cha->request_offer_id;
                }
            })
            ->editColumn('client.first_name', function ($cha) {
                return optional($cha->client)->first_name . ' ' . optional($cha->client)->last_name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOffer(Chat $chat)
    {
        $chat = $chat->load(['messages' => function($query) {
            $query->orderBy('id', 'asc');
        }]);
        return view('backend.chat.details', compact('chat'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showRequestOffer(Chat $chat)
    {
        $chat = $chat->load(['messages' => function($query) {
            $query->orderBy('id', 'asc');
        }, 'client']);
        return view('backend.chat.details', compact('chat'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string',
        ]);


        $inputs = $request->only('message');
        $inputs['chat_id'] = $chat ->id;
        $inputs['type'] = 3;

        ChatMessage::create($inputs);

        $notify['client_id'] = $chat->client_id;
        $notify['chat_id']   = $chat ->id;
        $notify['user_type'] = 3;
        $notify['number']    = $chat->offer_id == null ? $chat->request_offer_id : $chat->offer_id;
        $notify['type']      = 'chat';

        $this->sendNotification($notify);

        return back()->with('success', 'success');
    }

    
}
