@extends('layouts.main')

@section('content')
<div id='app'></div>

<script type="text/html" id="template-vue">
    <!-- Ref: https://next.vuetifyjs.com/en/components/application/#api -->
    <v-app>
        <v-navigation-drawer app>
            <!-- -->
        </v-navigation-drawer>

        <v-app-bar app>
            <h1>{{ config('app.name') }}</h1>
        </v-app-bar>

        <!-- Sizes your content based upon application components -->
        <v-main>

            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-card
                            class="pa-5 text-center"
                            tile
                        >
                            <v-file-input
                                label="Select data file"
                                show-size
                                v-model="dataFile"
                            />
                            <v-btn
                                color="primary"
                                elevation=5
                                x-large
                                :disabled="!dataFile || dataFile.length == 0 || isLoading"
                                @click="uploadFile"
                            >
                                @{{ isLoading ? 'Uploading...' : 'Upload' }}
                            </v-btn>
                            <!-- @{{ dataFile }} -->
                        </v-card>
                    </v-col>
                </v-row>
                <v-row style="padding-top:20px;" v-if="percentageOfNumberOne && !isLoading">
                    <v-col cols="12" sm="6">
                        <bar-chart
                            bar-data-background-color="blue"
                            percentage=30
                            title="Expected Result"
                        />
                    </v-col>
                    <v-col cols="12" sm="6">
                        <bar-chart
                            bar-data-background-color="red"
                            :percentage="percentageOfNumberOne"
                            title="Actual Result"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-main>

        <v-footer app>
            <div class="ml-auto">
                <!-- Tip: Use @ for Laravel Blade to ignore curly braces and allow Vue to process it -->
                Developed with ❤️ by Ivan Lim |  v {{ config('app.version') }}
            </div>
        </v-footer>
    </v-app>
</script>

<script>
    const app = Vue.createApp({
        name: 'app',

        setup() {
            const { reactive, ref, version } = Vue;

            // === data ===
            const dataFile = ref(null);
            const isLoading = ref(false);
            const page = reactive({
                version: '1.0.5',
            });
            const percentageOfNumberOne = ref(null);

            // === methods ===
            const uploadFile = async () => {
                if (!dataFile.value) {
                    // This should have been prevented by v-btn `disabled` prop
                    return alert('Please select a file');
                }

                isLoading.value = true;

                // console.log('[welcome.blade uploadFile] dataFile.value[0]', dataFile.value[0]);       // For debug only

                const formData = new FormData();
                const requestInputFieldName = 'data_file';
                formData.append(requestInputFieldName, dataFile.value[0]);
                const options = {
                    method: 'post',
                    body: formData,
                };
                const url = '/api/upload-data-file';
                const response = await fetch(url, options);
                const jsonResponse = await response.json();

                if (jsonResponse.results) {
                    // Calculate percentage of number 1
                    processedResult = jsonResponse.results
                        .reduce((result, num) => {
                            return {
                                noOfOnes: result.noOfOnes + (num == 1 ? 1 : 0),
                                noOfItems: result.noOfItems + 1,
                            };
                        }, {
                            noOfOnes: 0,
                            noOfItems: 0,
                        })
                    // console.log('[welcome.blade uploadFile] processedResult', processedResult);       // For debug only
                    percentageOfNumberOne.value = Math.round(processedResult.noOfOnes / processedResult.noOfItems * 100);
                } else {
                    percentageOfNumberOne.value = null;
                }
                // console.log('[welcome.blade uploadFile] percentageOfNumberOne', percentageOfNumberOne.value);       // For debug only

                isLoading.value = false;

                // console.log('[welcome.blade uploadFile] jsonResponse', jsonResponse);       // For debug only
            };


            return { dataFile, isLoading, page, percentageOfNumberOne, uploadFile, version };
        },

        template: '#template-vue',
    });

    const vuetify = Vuetify.createVuetify();

    // v-card: https://vuetifyjs.com/en/components/cards/
    // d-flex: https://vuetifyjs.com/en/styles/flex
    app.component('bar-chart', {
        template: `
            <v-card elevation=5 :title="title">
                <v-card-subtitle>
                    Percentage of the number 1: &nbsp;<strong>@{{ percentage }}%</strong>
                </v-card-subtitle>
                <div class="d-flex justify-center">
                    <div
                        class="mt-4 d-flex flex-column"
                        :style="{ background: barBackgroundColor, height: barHeight + 'px', width: barWidth + 'px' }"
                    >
                        <div
                            class="mt-auto"
                            :style="{ background: barDataBackgroundColor, height: barDataHeight + 'px' }"
                        ></div>
                    </div>
                </div>
            </v-card>
        `,

        props: [
            'barDataBackgroundColor',
            'percentage',
            'subtitle',
            'title'
        ],

        data() {
            return {
                barBackgroundColor: '#eee',
                barHeight: 200,
                barWidth: 50,
            };
        },

        computed: {
            barDataHeight() {
                return this.percentage / 100 * this.barHeight;
            },
        },
    });

    app.use(vuetify);

    app.mount("#app");
</script>
@endsection