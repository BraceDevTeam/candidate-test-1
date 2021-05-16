<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Tag;
use App\OrderTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('orders.index')->withOrders(Order::paginate(10));
    }

    private function getAllCustomers() {
        return Customer::select(
            'id',
            'first_name',
            'last_name',
            'email'
        )->get();
    }

    private function getAllTags() {
        return Tag::select(
            'id',
            'name',
        )->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_customers = $this->getAllCustomers();
        $all_tags = $this->getAllTags();
        return view('orders.create', compact(['all_customers', 'all_tags']))->withOrder(new Order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'cost' => 'required',
            'customer_id' => 'required',
            'tags_id' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->route('orders.create')->withMessage($validator->errors());
        }
        
        $order = Order::create($request->all());
        
        foreach($request->tags_id as $tag_id)
        {
            $ordertag = new OrderTag;   
            $ordertag->order_id = $order->id;
            $ordertag->tag_id = $tag_id;
            $ordertag->save();
        }

        return redirect()->route('orders.edit', $order->id)->withMessage('Order created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($order_id)
    {
        $all_customers = $this->getAllCustomers();
        $all_tags = $this->getAllTags();

        $order = Order::select(
            'orders.id AS id',
            'orders.title AS title',
            'orders.description AS description',
            'orders.cost AS cost',
            'orders.customer_id AS customer_id',
            'customers.first_name AS customer_first_name',
            'customers.last_name AS customer_last_name'
        )
        ->join('customers', 'orders.customer_id', '=', 'customers.id')
        ->whereNull('orders.deleted_at')
        ->whereNull('customers.deleted_at')
        ->where('orders.id', $order_id)
        ->get()->first();

        return view('orders.edit', compact(['order', 'all_customers', 'all_tags']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $all_customers = $this->getAllCustomers();
        $all_tags = $this->getAllTags();

        $order->update($request->all());

        $order = Order::select(
            'orders.id AS id',
            'orders.title AS title',
            'orders.description AS description',
            'orders.cost AS cost',
            'orders.customer_id AS customer_id',
            'customers.first_name AS customer_first_name',
            'customers.last_name AS customer_last_name'
        )
        ->join('customers', 'orders.customer_id', '=', 'customers.id')
        ->whereNull('orders.deleted_at')
        ->whereNull('customers.deleted_at')
        ->where('orders.id', $order->id)
        ->get()->first();

        return view('orders.edit', compact(['order', 'all_customers', 'all_tags']))->withMessage('Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->withMessage('Order deleted successfully');
    }
}
