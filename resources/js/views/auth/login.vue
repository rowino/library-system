<template>
  <v-layout align-center justify-center>
    <v-flex xs12 sm8 md4>

      <v-card class="elevation-12">
        <v-toolbar dark color="primary">
          <v-toolbar-title>Login form</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form @submit.prevent="onLogin">
            <v-text-field
                prepend-icon="envelope"
                label="Email"
                type="email"
                v-model="email"
            />
            <v-text-field
                prepend-icon="lock"
                label="Password"
                type="password"
                v-model="password"
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="onLogin">Login</v-btn>
        </v-card-actions>
      </v-card>

    </v-flex>
  </v-layout>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    async onLogin() {
      try {
        const { data } = await axios.post('api/login', {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem('token', data.token);
        this.$root.$emit('set-user', data.user);
        this.$router.push('/');
      } catch (e) {

      }
    }
  },
}
</script>
