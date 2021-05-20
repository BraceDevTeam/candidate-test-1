<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;

class ContractsController extends Controller
{
    public function index() {
        $contracts = Contract::select(
                'contracts.id AS id',
                'customers.first_name AS customer_first_name',
                'customers.last_name AS customer_last_name',
                'orders.title AS order_title',
            )
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->join('orders', 'contracts.order_id', '=', 'orders.id')
            ->paginate(10);

        return view('contracts.index', ['contracts' => $contracts]);
    }
}
