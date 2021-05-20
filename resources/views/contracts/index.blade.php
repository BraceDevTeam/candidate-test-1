@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Customer name</th>
          <th scope="col">Order title</th>
        </tr>
      </thead>
      <tbody>
        @foreach($contracts as $contract)
          <tr>
            <th scope="row">{{ $contract->id }}</th>
            <td>{{ $contract->customer_first_name }} {{ $contract->customer_last_name }}</td>
            <td>{{ $contract->order_title }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    {{ $contracts->links() }}
  </div>
</div>

@stop
