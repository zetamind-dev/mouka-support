<?php

namespace ComplainDesk\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use ComplainDesk\Category;
use ComplainDesk\Ticket;

class TicketsViewExport implements FromView
{
    use Exportable;

        public function __construct($status, $location)
    {
        $this->status = $status;
        $this->location = $location;

    }

    /**
     * @return \Illuminate\Support\View
     */
    public function view(): View
    {
        return view('reports.filter-table', [
            'tickets' => Ticket::all()->where('status', $this->status)->where('location', $this->location),
            'categories' => Category::all()
        ]);
    }
}
