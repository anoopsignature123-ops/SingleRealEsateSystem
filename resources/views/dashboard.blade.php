@extends('layouts.app')
@section('content')
  
<div class="container-fluid">
    <div class="row">

      <!-- Earnings -->
      <div class="col-md-3">
        <div class="card bg-warning text-white p-3">
          <h4>$30200</h4>
          <p>All Earnings</p>
        </div>
      </div>

      <!-- Views -->
      <div class="col-md-3">
        <div class="card bg-success text-white p-3">
          <h4>290+</h4>
          <p>Page Views</p>
        </div>
      </div>

      <!-- Task -->
      <div class="col-md-3">
        <div class="card bg-danger text-white p-3">
          <h4>145</h4>
          <p>Task Completed</p>
        </div>
      </div>

      <!-- Downloads -->
      <div class="col-md-3">
        <div class="card bg-info text-white p-3">
          <h4>500</h4>
          <p>Downloads</p>
        </div>
      </div>

    </div>
  </div>
@endsection