@extends('master')

@section('title', 'Virtual Wallet')

@section('content')
    <router-link to="/users">Users</router-link> -
    <router-link to="/departments">Departments</router-link> -

    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vue.js"></script>
 @stop
