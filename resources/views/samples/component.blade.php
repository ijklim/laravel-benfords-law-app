@extends('layouts.main')

@section('content')
<div id='app'></div>

<script type="text/html" id="template-vue">
    <h1>Vue3 Component</h1>

    <app-component />
</script>

<script>
    const app = Vue.createApp({
        name: 'app',

        template: '#template-vue',
    });

    app.component('app-component', {
        template: '<h2>This is a Vue Component! v@{{ version }}</h2>',

        data() {
            return {
                version: '1.0.0',
            };
        },
    });

    app.mount("#app");
</script>
@endsection
