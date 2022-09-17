<template>
  <v-menu v-if="user.id" bottom min-width="200px" rounded offset-y>
    <template v-slot:activator="{ on }">
      <v-btn icon v-on="on">
        <v-avatar color="grey" size="28">
          <span class="white--text">{{ initials }}</span>
        </v-avatar>
      </v-btn>
    </template>
    <v-card>
      <v-list-item-content class="justify-center">
        <div class="mx-auto text-center">
          <v-avatar color="grey" class="mb-2">
            <span class="white--text text-h5">{{ initials }}</span>
          </v-avatar>
          <h3>{{ user.name }}</h3>
          <p class="text-caption mt-1">{{ user.email }}</p>
          <v-divider class="my-3"></v-divider>
          <v-btn depressed rounded text @click="onLogout">
            Logout
          </v-btn>
        </div>
      </v-list-item-content>
    </v-card>
  </v-menu>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  computed: {
    initials() {
      return ( this.user.name || '' ).split(' ').map(name => name.substring(0, 1)).join('')
    }
  },
  methods: {
    onLogout() {
      localStorage.removeItem('token')
      this.user = {};
      this.$router.push('/login');
    }
  }
}
</script>
