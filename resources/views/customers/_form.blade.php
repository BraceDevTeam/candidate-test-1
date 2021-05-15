<div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required="true">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required="true">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required="true">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Phone number</label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required="true">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Company</label>
          <input type="text" name="company" class="form-control" value="{{ old('company', $customer->company) }}" required="true">
        </div>
      </div>
    </div>
