<template>
  <div>
    <div class="relative bg-blueGray-100">
      <dashboard-navbar />
      <header-stats />
      <div class="px-4 md:px-10 mx-auto w-full -m-24">
        <Dashboard/>
        <router-view />
        <footer-admin />
      </div>
    </div>
  </div>
</template>
<script type="module">
import DashboardNavbar from "./Navbars/DashboardNavbar.vue";
import HeaderStats from "./Headers/HeaderStats.vue";
import FooterAdmin from "./Footers/FooterAdmin.vue";

import Dashboard from './Dashboard.vue';
export default {
  name: "admin-layout",
  components: {
    DashboardNavbar,
    HeaderStats,
    FooterAdmin,
    Dashboard
  },
};
</script>
