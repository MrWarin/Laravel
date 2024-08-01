<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public static function getAllTicket($start = 0, $end = 20)
    {
        $num_rows = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
        ->count();

        $ticket = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
        ->offset($start)
        ->limit($end)
        ->get(['tickets.*','users.name']);

        return ['ticket' => $ticket, 'rows' => $num_rows];
    }
}
