<template>
  <v-app>

    <v-app-bar app>
      <v-app-bar-nav-icon></v-app-bar-nav-icon>

      <v-toolbar-title>Library System</v-toolbar-title>
      <v-btn class="ml-3" to="/" text>Libraries</v-btn>
      <v-spacer></v-spacer>

      <header-search/>

      <header-user :user="user"/>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main>

      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view/>
      </v-container>
    </v-main>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
import { defineComponent } from '@vue/composition-api'
import HeaderSearch from './components/HeaderSearch.vue';
import HeaderUser from "./components/HeaderUser.vue";

export default defineComponent({
  name: 'App',
  components: { HeaderUser, HeaderSearch },
  data() {
    return {
      user: {},
    }
  },
  mounted() {
    this.getUser();
    this.$root.$on('set-user', (user) => {
      this.user = user;
    });
  },
  methods: {
    async getUser() {
      const { data } = await axios.get('/api/user');
      this.user = data
    },
  }
})
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
