@extends('master')

@section('title', 'Virtual Wallet')

@section('content')
<router-view></router-view>
@endsection

@section('pagescript')
  <script src="js/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
@stop
