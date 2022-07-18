@extends('layout')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Simple CRM APP</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('accounts.create') }}"> Create New Account</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($accounts as $account)
        <tr>
            <td>{{ ($loop->index)+1 }}</td>
            <td>{{ $account->name }}</td>
            <td>
                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST">
   
                    <a class="btn btn-info" href="{{ route('accounts.show',$account->id) }}">Show</a>
    
                    <a class="btn btn-primary" href="{{ route('accounts.edit',$account->id) }}">Edit</a>
   
                    @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    <div class="pull-right">
                <a class="btn btn-success" href="{{ route('dashboard') }}"> BACK</a>
    </div>
  
    <!-- {!! $accounts->links() !!} -->
      
@endsection