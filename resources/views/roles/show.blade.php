@extends('dashboards._admin_header')
@section('index-content')
<h1> Modalisa </h1>
<ul>
    {{--    {{ $role->name }}--}}
</ul>

<div id="app">
    <modalisa-modal v-show="showModal" @close="showModal = false"></modalisa-modal>
    <button class="btn-green py-2" v-on:click="showModal = true"> Show Modal</button>
</div>
{{--<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}}
{{--<script>--}}
{{--    // vue.component('modalisa-msg', {--}}
{{--    //     props: ['title', 'body'],--}}
{{--    //     template:--}}
{{--    //         `--}}
{{--    //        <div class="text-red-500">--}}
{{--    //                 <h1> This is title</h1>--}}
{{--    //                 @{{title}}--}}
{{--    //             </div>--}}
{{--    //             <div class="text-green-600">--}}
{{--    //                 <h3> this is body</h3>--}}
{{--    //                 @{{ body }}--}}
{{--    //             </div>--}}
{{--    //       `--}}
{{--    // })--}}
{{--    //--}}
{{--    // new Vue({--}}
{{--    //     el: '#app'--}}
{{--    // });--}}
{{--</script>--}}
@endsection
