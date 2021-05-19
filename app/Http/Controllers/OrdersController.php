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

    private function getTagsByOrderId($order_id) {
        return OrderTag::select(
            'orders_tags.tag_id AS tag_id',
            'tags.name AS tag_name'
            )
            ->join('tags', 'orders_tags.tag_id', '=', 'tags.id')
            ->where('order_id', $order_id)
            ->get();
    }
    
    public function orderValidator($request)
    {
        $validator = Validator::make($request, [
            'title' => 'required',
            'description' => 'required',
            'cost' => 'required',
            'customer_id' => 'required',
            'tags_id' => 'required'
        ]);
            
        return $validator;
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
        $tags = null;

        return view('orders.create', compact(['all_customers', 'all_tags', 'tags']))->withOrder(new Order);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->orderValidator($request->all());

        if($validator->fails()) {
            return redirect()->route('orders.create')->withMessage($validator->errors());
        }

        try{
            $order = Order::create($request->all());

            foreach($request->tags_id as $tag_id){
                $ordertag = new OrderTag;
                $ordertag->order_id = $order->id;
                $ordertag->tag_id = $tag_id;
                $ordertag->save();
            }

            return redirect()->route('orders.edit', $order->id)->withMessage('Order created successfully.');  
        }
        catch(Exception $e){
            return redirect()->route('orders.create', $order->id)->withMessage('Order not created.');  
        }
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
        $tags = $this->getTagsByOrderId($order_id);

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

        return view('orders.edit', compact(['tags', 'all_tags', 'order', 'all_customers']));
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
        $validator = $this->orderValidator($request->all());

        if($validator->fails()) {
            return redirect()->route('orders.edit', compact(['order', 'all_customers', 'all_tags']))->withMessage($validator->errors());
        }

        try {
            $order->update($request->all());

            foreach($request->tags_id as $tag_id){
                $order_tag_by_orderId = OrderTag::select()
                ->where('order_id', $order->id)
                ->where('tag_id',$tag_id)
                ->get()->first();
                if (!$order_tag_by_orderId){
                    $ordertag = new OrderTag;
                    $ordertag->tag_id = $tag_id;
                    $ordertag->order_id =  $order->id;
                    $ordertag->save();
                }
            }
            return redirect()->route('orders.edit', $order->id)->withMessage('Order updated successfully.');  
        } catch(\Exception $exception) {
            return redirect()->route('orders.edit', $order->id)->withMessage('An error occured, retry.');
        }
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
