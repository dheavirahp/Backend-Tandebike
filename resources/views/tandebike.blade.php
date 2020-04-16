<?php
date_default_timezone_set('Asia/Jakarta');
?>

<html>
<head>
	<title>Tandebike</title>
</head>

<body>
	<h1>Skripsi yahud</h1>

	<@extends('base')

@section('main')
<div class="row">
<div class="col-sm-12">
    <h1 class="display-3">Contacts</h1>    
  <table class="table table-striped">
    <thead>
        <tr>
          <td>ID</td>
          <td>Name</td>
          <td>Nomor Telepon</td>
          <td>Email</td>
          <td>Password</td>
          <td colspan = 2>Actions</td>
        </tr>
    </thead>
    <tbody>
        @foreach($users ?? '' as $user)
        <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->nama}}</td>
            <td>{{$user->noTelp}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->password}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>
</div>
@endsection	
    </body>
</html>