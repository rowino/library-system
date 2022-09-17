<template>
  <div>
    <h1>{{ library.name }}</h1>
    <v-list two-line>
      <v-list-item-group v-model="selected" multiple>
        <template v-for="(book, index) in books">
          <v-list-item :key="book.id">
            <template v-slot:default="{ active }">
              <v-list-item-content @click="openBook(book)">
                <v-list-item-title v-text="book.title"/>
                <v-list-item-subtitle class="text--primary" v-text="book.author"/>
                <v-list-item-subtitle v-text="book.description"/>
              </v-list-item-content>

              <v-list-item-action @click="toggleLike(book, index)">
                <v-list-item-action-text v-text="`${book.pivot.quantity} Books`"/>
                <v-icon v-if="!book.liked" color="grey lighten-1">
                  mdi-star-outline
                </v-icon>
                <v-icon v-if="book.liked" color="yellow darken-3">
                  mdi-star
                </v-icon>
              </v-list-item-action>
            </template>
          </v-list-item>
          <v-divider v-if="index < books.length - 1" :key="`divvider-${index}`"/>
        </template>
      </v-list-item-group>
    </v-list>
    <div v-if="books.length < pagination.total" class="text-center">
      <v-btn :loading="loading" @click="getBooks(pagination.current_page +1)">Load More</v-btn>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      library: {},
      pagination: {},
      books: [{}],
      selected: [],
      loading: false,
    }
  },
  created() {
    this.getLibrary();
    this.getBooks(1);
  },
  methods: {
    async getLibrary() {
      const { data } = await axios.get(`/api/libraries/${ this.$route.params.id }`);
      this.library = data;
    },
    async getBooks(page) {
      this.loading = true;
      if (page === 1) {
        this.books = [];
      }
      try {


        const {
          data: {
            data,
            ...pagination
          }
        } = await axios.get(`/api/libraries/${ this.$route.params.id }/books`, { params: { page } });
        this.books = [...this.books, ...data];
        this.pagination = pagination;
      } finally {
        this.loading = false;
      }
    },
    openBook({ id }) {
      this.$router.push(`/book/${ id }`)
    },
    async toggleLike({ id, liked, pivot }, index) {
      const { data } = await axios.post(`/api/books/${ id }/like`, { liked: !liked });
      this.$set(this.books, index, { ...data, pivot });
    },
  },
}
</script>
