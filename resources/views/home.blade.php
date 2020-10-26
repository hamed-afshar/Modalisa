@extends('layouts.app')
@section('content')
    <!-- Dashboard for normal users -->
    <div id="app">
        @guest
            <guest-navbar></guest-navbar>
        @endguest
        @auth
            <general-navbar></general-navbar>
        @endauth
        <users-sidebar></users-sidebar>
    </div>
@endsection
<script>

</script>
