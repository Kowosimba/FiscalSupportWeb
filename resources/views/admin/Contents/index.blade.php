@extends('layouts.contents')
@section('content')
  
<ul class="panel-nav nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" 
           href="{{ route('admin.index') }}">
           Faults Allocation
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('content.index') ? 'active' : '' }}" 
           href="{{ route('content.index') }}">
           Manage Content
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Call Logs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Customer Contacts</a>
    </li>
</ul>

<!-- #region -->  
    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h1>Contents</h1>
            <p>Welcome to the contents page!</p>
        </div>
        </div>

        

@endsection