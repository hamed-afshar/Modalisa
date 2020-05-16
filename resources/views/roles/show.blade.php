<!DOCTYPE html>
<html>
<head>
    <title>

    </title>
</head>
<body>
<h1> Modalisa </h1>
<ul>
    {{--    {{ $role->name }}--}}
</ul>

<div id="app" v-model="name">
   <ul>
       <li v-for="name in names" v-text="name">
           @{{ name }}
       </li>
   </ul>
    <input id="input" type="text" v-model="newName">
    <button v-on:click="addName"> add name </button>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script>
    var app= new Vue({
        el: '#app',
        data: {
            newName: '',
            names: ["hamed", "amin", "ghazal"]
        },
        methods: {
            addName() {
                this.names.push(this.newName);
            }
        }
    })
</script>
</body>
</html>

