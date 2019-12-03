<?php

namespace ComplainDesk\Exports;

use ComplainDesk\Category;
use ComplainDesk\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TicketsViewExport implements FromView
{
    use Exportable;

    public function __construct($status, $location, $category, $date_from, $date_to)
    {
        $this->status = $status;
        $this->location = $location;
        $this->category = $category;
        $this->date_from = $date_from;
        $this->date_to = $date_to;

    }

    /**
     * @return \Illuminate\Support\View
     */
    public function view(): View
    {
        /**
         * SET STATUS QUERY PARAMS
         */
        $status_query = [];
        if ($this->status === 'all') {
            $status_query[0] = 'Open';
            $status_query[1] = 'Closed';
        } else {
            $status_query[0] = $this->status;
            $status_query[1] = $this->status;
        }

        /**
         * SET LOCATION QUERY PARAMS
         */
        $location_query = [];
        if ($this->location === 'all') {
            $location_query[0] = 'Lagos';
            $location_query[1] = 'Head Office';
            $location_query[2] = 'Benin';
            $location_query[3] = 'Kaduna';
        } else {
            $location_query[0] = $this->location;
            $location_query[1] = $this->location;
        }

        /**
         * SET CATEGORY QUERY PARAMS
         */
        $categories = Category::all();
        $category_query = [];
        // Iterate over the result set
        if ($this->category === 'all') {
            // set loop counter
            $index = 0;
            // iterate over the categories result set
            foreach ($categories as $category) {
                // set all categoru id to category_query array
                $category_query[$index] = $category->id;
                $index++;
            }

        } else {
            $category_query[0] = $this->category;
            $category_query[1] = $this->category;
        }

        return view('reports.filter-table', [
            'tickets' => Ticket::all()
                ->whereIn('status', $status_query)
                ->whereIn('category_id', $category_query)
                ->whereIn('location', $location_query)
                ->whereBetween('created_at', [$this->date_from, $this->date_to])
                ->where('drop_ticket', 0),

            'categories' => Category::all(),
        ]);
    }
}
