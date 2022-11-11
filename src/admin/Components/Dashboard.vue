<template>
    <div>
        <div class="flex flex-wrap">
            <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
                <card-line-chart />
            </div>
            <div class="w-full xl:w-4/12 px-4">
                <card-bar-chart />
            </div>
        </div>
        <div class="flex flex-wrap mt-4">
            <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
                <card-page-visits />
            </div>
            <div class="w-full xl:w-4/12 px-4">
                <card-social-traffic />
            </div>
        </div>
    </div>
</template>
<script type="module">
import CardLineChart from "./Cards/CardLineChart.vue";
import CardBarChart from "./Cards/CardBarChart.vue";
import CardPageVisits from "./Cards/CardPageVisits.vue";
import CardSocialTraffic from "./Cards/CardSocialTraffic.vue";
export default {
    name: "dashboard-page",
    components: {
        CardLineChart,
        CardBarChart,
        CardPageVisits,
        CardSocialTraffic,
    },
};
</script>
