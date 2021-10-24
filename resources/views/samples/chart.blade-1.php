@extends('layouts.main')

@section('content')
<div id='app'></div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script> -->
<!-- <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script> -->
<!-- <script src="https://cdn.skypack.dev/vue3-chart-v2" type="module"></script> -->

<script type="text/html" id="template-vue">
    <h1>Vue3 Chart</h1>

    <app-chart />
</script>

<script type="module">
    // Ref: https://www.skypack.dev/view/vue3-chart-v2
    // https://cdn.skypack.dev/vue3-chart-v2
    // Ͱ https://cdn.skypack.dev/-/vue3-chart-v2@v0.8.3-MFPjbqW4q4k4sTL8UHtK/dist=es2020,mode=imports/optimized/vue3-chart-v2.js
    import { Bar, Bubble, Doughnut, HorizontalBar, Line, Pie, PolarArea, Radar, Scatter, generateChart } from 'https://cdn.skypack.dev/vue3-chart-v2';        // Works
    // console.log('[chart.blade.php] Bar', Bar);                  // For debug only


    const app = Vue.createApp({
        name: 'app',

        template: '#template-vue',
    });

    // app.component('app-chart', {
    //     name: 'AppChart',
    //     extends: Bar,
    //     // mixins: [mixins.reactiveProp],

    //     data() {
    //         return {
    //             version: '1.0.1',
    //         };
    //     },

    //     mounted () {
    //         // Overwriting base render method with actual data
    //         const chartData = {
    //             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    //             datasets: [
    //                 {
    //                     label: 'GitHub Commits',
    //                     backgroundColor: '#f87979',
    //                     data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11],
    //                 }
    //             ],
    //         };
    //         // this.renderChart();
    //         // setTimeout(() => this.renderChart(), 2000);
    //     },
    // });

    // export default Vue.defineComponent({
    const AppChart = Vue.defineComponent({
        name: 'AppChart',
        // extends: Bar,
        extends: Line,

        setup() {
            const { onMounted, ref } = Vue;

            // // === data ===
            // const canvas = ref(null);

            // return { canvas };
            onMounted(() => {
                this.renderChart([1, 2, 3]);
            });
        },

        // mounted() {
        //     // debugger;
        //     console.log(this.$el);
        //     // console.log(this.$refs);
        //     // this.renderChart([1, 2, 3]);
        // },
    });

    app.component('app-chart', AppChart);

    app.mount("#app");
</script>
@endsection