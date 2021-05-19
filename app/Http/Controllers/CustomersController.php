<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Contract;
use App\Models\Order;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customers.index')->withCustomers(Customer::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create')->withCustomer(new Customer);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'required',
            'company' => 'required'
        ]);

        $email_validation_error = $validator->errors()->first('email') ?? null;

        $customer = new Customer($request->all());

        if ($validator->fails()) {
            if($email_validation_error) {
                return redirect()->route('customers.create')->withMessage($email_validation_error);
            }
        }

        $customer->save();

        return redirect()->route('customers.edit', $customer)->withMessage('Customer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());

        return view('customers.edit', compact('customer'))->withMessage('Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer) {
        try {
            $customer->delete();

            $contracts_to_delete = Contract::select()
                ->where('customer_id', $customer->id)
                ->get();

            $orders_to_delete = Order::select()
                ->where('customer_id', $customer->id)
                ->get();

            if($contracts_to_delete) {
                foreach($contracts_to_delete as $contract_to_delete) {
                    $contract_to_delete->delete();
                }
            }

            if($orders_to_delete) {
                foreach($orders_to_delete as $order_to_delete) {
                    $order_to_delete->delete();
                }
            }

            return redirect()->route('customers.index')->withMessage('Customer deleted successfully');
        } catch(Exception $exception) {
            return redirect()->route('customers.index')->withMessage('An error occured, retry...');
        }
    }
}
