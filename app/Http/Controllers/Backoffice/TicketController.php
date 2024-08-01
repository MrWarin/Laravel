<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function index($page = 1)
    {
        $per_page = 20;
        $start = ($page * $per_page) - $per_page;
        $item = Ticket::getAllTicket($start, $per_page);
        $ticket = $item['ticket'];
        $num_rows = $item['rows'];

        $page_num = ceil($num_rows / $per_page);
        
        return view('backoffice/ticket', [
            'tickets' => $ticket, 
            'page' => $page,
            'page_num' => $page_num
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = new Ticket;
        $data = $request->except('_token');
        foreach($data as $name => $value)
        {
            $ticket->$name = $value;
        }

        $ticket->save();

        return back()->with('message','Insert Successful !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::getAllTicket();
        $edit = Ticket::find($id);

        return view('backoffice/ticket', ['tickets' => $ticket, 'edit' => $edit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $data = $request->except('_method', '_token');
        foreach($data as $name => $value)
        {
            if($ticket->$name !== $value)
            {
                $ticket->$name = $value;
            }
        }

        $ticket->save();

        return back()->with('message','Update Successful !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();

        return back()->with('message','Delete Successful !');
    }
}
