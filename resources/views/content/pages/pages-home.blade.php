@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<h4>Home Page</h4>
<div class="mb-6">
<p>Selamat datang <a href="{{route('profile.show')}}">{{auth()->user()->name}}</a> di {{config('variables.templateName')}}.</p>
</div>
<div class="row mb-6">
  <div class="col-6">
    <div class="card">
        <div class="card-header">
          <h5 class="card-title">Informasi Profile</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">Nama</div>
                <div class="col-md-8">: {{$user->name}}</div>
            </div>
            <div class="row">
                <div class="col-md-4">Email</div>
                <div class="col-md-8">: {{$user->email}}</div>
            </div>
        </div>
    </div>
  </div>
  <div class="col-6">
  @livewire('profile.logout-other-browser-sessions-form')
  </div>
</div>
@endsection
