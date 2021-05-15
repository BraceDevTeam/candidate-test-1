<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $order->title) }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Description</label>
      <input type="text" name="description" class="form-control" value="{{ old('description', $order->description) }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Cost</label>
      <input type="number" name="cost" class="form-control" value="{{ old('cost', $order->cost) }}">
    </div>
  </div>
  
</div>
