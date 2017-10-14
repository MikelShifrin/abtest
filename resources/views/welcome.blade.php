@extends('layouts.master')
@section('content')
<div class="container">

      <form class="form-signin" action="{{route('submit')}}" method='post'>
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only" >Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        {{csrf_field()}}
      </form>

    </div> <!-- /container -->
@endsection