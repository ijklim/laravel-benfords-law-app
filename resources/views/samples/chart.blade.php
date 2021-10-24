@extends('layouts.main')

@section('content')
<div id='app'></div>

<!-- https://bl.ocks.org/agnjunio/fd86583e176ecd94d37f3d2de3a56814 -->
<script src="https://d3js.org/d3.v5.min.js"></script>

<script type="text/html" id="template-vue">
    <h1>Vue3 Chart</h1>

    <app-chart />
</script>

<script>
    const CANVAS = {
        margin: {
            top: 20,
            right: 100,
            bottom: 50,
            left: 50,
        },
        xDomainOriginShift: 5,
        yDomainOriginShift: 1,
        circleRadius: 8,
        color: 'green',
    };

    const app = Vue.createApp({
        name: 'app',

        template: '#template-vue',
    });

    // export default Vue.defineComponent({
    const AppChart = Vue.defineComponent({
        name: 'AppChart',

        data() {
            return {
                axis: {
                    x: {},
                    y: {},
                },
                d3Data: [
                    { x:1, y:30, },
                    { x:2, y:5, },
                    { x:3, y:10, },
                ],
                ddd: {},
                graphHeight: 500,
                graphWidth: 1000,
                id: 'd3-' + Math.round(Math.random() * 1000000),
            };
        },

        computed: {
            chartHeight () {
                return this.graphHeight - CANVAS.margin.top - CANVAS.margin.bottom;
            },
            chartWidth () {
                return this.graphWidth - CANVAS.margin.right - CANVAS.margin.left;
            },
        },

        methods: {
            /**
             * Standard code to create a d3 element
             *
             * @param {Object} param0
             */
            createD3Element ({
                data = [],
                transformX = CANVAS.margin.left + 1,
                transformY = 0,
                type = '',
            } = {}) {
                let result = this.ddd.svg
                    .append('g')
                    .attr('transform', `translate(${transformX}, ${transformY})`);
                if (type) {
                    result = result
                        .selectAll(type)
                        .data(data)
                        .enter()
                        .append(type);
                }
                return result;
            },

            drawData() {
                // translate(x, y) specifies where bar begins, +1 to move right of y axis
                // scatterplot uses circle instead of rect
                this.ddd.chart = this
                    .createD3Element({
                        data: this.d3Data.map(d => ({
                            x: d.x,
                            y: d.y,
                        })),
                        type: 'circle',
                    })
                    .attr('fill', d => CANVAS.color)
                    .attr('r', _ => CANVAS.circleRadius)
                    .attr('cx', d => this.axis.x.scale(d.x))
                    .attr('cy', d => this.axis.y.scale(d.y - 1.5));
            },

            drawGuide () {
                // Y Guide
                // translate(x, y) specifies where y axis begins, drawn from top to bottom
                this
                    .createD3Element({
                        transformX: CANVAS.margin.left,
                        transformY: CANVAS.margin.top,
                    })
                    .call(d3
                        .axisLeft(this.axis.y.values)
                        .ticks(5)
                    );

                // X Guide
                // transform(x, y) specifies where x axis begins, drawn from left to right
                this
                    .createD3Element({
                        transformX: CANVAS.margin.left,
                        transformY: CANVAS.margin.top + this.chartHeight,
                    })
                    .call(d3
                        .axisBottom(this.axis.x.values)
                        .ticks(10)
                    );
            },
        },

        mounted() {
            // Y values and scaling function
            const yArray = this.d3Data.map(a => a.y);
            const yDomain = [0, d3.max(yArray) + CANVAS.yDomainOriginShift];
            const yRange = [this.chartHeight, 0];
            this.axis.y.values = d3
                .scaleLinear()
                // Labels on axis, should be equal to or larger than dataset
                .domain(yDomain)
                // Together with yGuide translate below, determines where to start drawing the axis
                .range(yRange);

            // this.axis.y.scale becomes a function that converts a y value to a y position
            this.axis.y.scale = d3
                .scaleLinear()
                .domain([0, d3.max(yArray) + CANVAS.yDomainOriginShift])
                .range([this.chartHeight, 0]);

            // X values and scaling function
            const xArray = this.d3Data.map(a => a.x);
            const xDomain = [0, d3.max(xArray) + CANVAS.xDomainOriginShift];
            const xRange = [0, this.chartWidth];
            this.axis.x.values = d3
                .scaleLinear()
                .domain(xDomain)
                .range(xRange);

            // this.axis.x.scale becomes a function that converts a x value to a x position
            this.axis.x.scale = d3
                .scaleLinear()
                .domain(xDomain)
                .range(xRange);

            d3.select(`#${this.id}`)
                .append('svg')
                .attr('width', this.chartWidth + CANVAS.margin.right + CANVAS.margin.left)
                .attr('height', this.chartHeight + CANVAS.margin.top + CANVAS.margin.bottom);
            this.ddd.svg = d3.select(`#${this.id} svg`);

            this.drawGuide();
            this.drawData();
        },

        template: `
            <div :id="id" style="border:1px solid #ccc; margin:10px;"></div>
        `,
    });

    app.component('app-chart', AppChart);

    app.mount("#app");
</script>
@endsection
