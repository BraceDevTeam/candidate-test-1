<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $order->title) }}" required="true">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Select customer</label>
      <select class="form-control" name="customer_id" required="true">
        @if ($order->customer_id)
        <option value = "{{ $order->customer_id }}" >{{ $order->customer_first_name }} {{ $order->customer_last_name }} </option>
        @endif
        @foreach($all_customers as $customer)
        @if ($customer->id != $order->customer_id)
        <option value = "{{ $customer->id }}" >{{ $customer->first_name }} {{ $customer->last_name }}</option>
        @endif
        @endforeach
      </select>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Cost</label>
      <input type="number" name="cost" class="form-control" value="{{ old('cost', $order->cost) }}" required="true">
    </div>
  </div>
  <div class="col-md-8">
    <div class="form-group">
      <label>Select tags</label>
      <select multiple class="form-control" name="tag_ids[]" id="select2_tags" required="true">
        @foreach($all_tags as $tag)
        <option name = "tag_{{ $tag->id }}" value = "{{ $tag->id }}" > {{ $tag->name }} </option>
        @endforeach
      </select>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control" value="{{ old('description', $order->description) }}" required="true"></textarea>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#select2_tags').select2();
  });
</script>