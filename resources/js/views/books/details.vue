<template>
  <v-card class="mx-auto" max-width="860">
    <v-card-actions>
      <v-spacer></v-spacer>
      <div @click="toggleLike">
        <v-icon v-if="!book.liked" color="grey lighten-1">
          mdi-star-outline
        </v-icon>
        <v-icon v-if="book.liked" color="yellow darken-3">
          mdi-star
        </v-icon>
      </div>
    </v-card-actions>
    <v-list-item two-line>
      <v-list-item-content>
        <v-list-item-title class="text-h5">
          {{ book.title }}
        </v-list-item-title>
        <v-list-item-subtitle>{{ book.author }}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <v-card-text>
      {{ book.description }}
    </v-card-text>

    <v-subheader>Libraries</v-subheader>

    <v-list class="transparent">
      <v-list-item v-for="library in book.libraries" :key="library.id" @click="openLibrary(library)">
        <v-divider/>
        <v-list-item-title>{{ library.name }}</v-list-item-title>
        <v-list-item-subtitle class="text-right">
          {{ library.pivot.quantity }}
        </v-list-item-subtitle>
      </v-list-item>
    </v-list>

  </v-card>
</template>

<script>
export default {
  name: '',
  data() {
    return {
      book: {}
    }
  },
  created() {
    this.getBook()
  },
  methods: {
    async getBook() {
      const { data } = await axios.get(`/api/books/${ this.$route.params.id }`);
      this.book = data;
    },
    async toggleLike() {
      const { data } = await axios.post(`/api/books/${ this.book.id }/like`, { liked: !this.book.liked });
      this.book = { ...this.book, ...data };
    },
    openLibrary({ id }) {
      this.$router.push(`/libraries/${ id }`)
    },
  },

}
</script>
