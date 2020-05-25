@extends('dashboards._admin_header')
@section('index-content')
    <div id="app">
        <customized-table v-bind:columns ="['id','name','date']"></customized-table>
    </div>
@endsection


