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
        <li>
            <task> Go to School</task>
            <task> Go to Work</task>
            <task> Go Home</task>
        </li>
    </ul>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script>
    vue.component('task-list', {
        'template: <div> <task v-for="student in students"> @{{student.name}} </task> </div>',
        data() {
            return {
                students: [
                    {name: "hamed", graduated: true}
                    {name: "amin", graduated: true}
                    {name: "ghazal", graduated: true}
                    {name: "shadi", graduated: true}
                ]
            }
        }
    })
    Vue.component('task', {
        template: '<li><slot></slot></li>'
    })

    new Vue({
        el: '#app'
    });
</script>
</body>
</html>

