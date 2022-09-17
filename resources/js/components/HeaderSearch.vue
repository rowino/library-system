<template>
  <div>
    <v-autocomplete
        v-model="selected"
        :items="books"
        :loading="isLoading"
        :search-input.sync="searchTerm"
        placeholder="Search for a book..."
        prepend-inner-icon="mdi-magnify"
        clearable
        cache-items
        hide-details
        hide-selected
        item-text="title"
        item-value="id"
        return-object
        solo
        flat
        @change="openBook"
    >
      <template v-slot:no-data>
        <v-list-item>
          <v-list-item-title>
            Search for your favorite
            <strong>book</strong>
          </v-list-item-title>
        </v-list-item>
      </template>
      <template v-slot:item="{ item }">
        <v-list-item-content>
          <v-list-item-title v-text="item.title"></v-list-item-title>
          <v-list-item-subtitle v-text="item.author"></v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <v-icon>mdi-book</v-icon>
        </v-list-item-action>
      </template>
    </v-autocomplete>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchTerm: 'minima',
      books: [],
      isLoading: false,
      selected: null
    }
  },
  watch: {
    searchTerm: 'getBooks'
  },
  methods: {
    async getBooks(search) {
      this.isLoading = true;
      try {
        const { data: { data } } = await axios.get(`/api/books`, { params: { search } });
        this.books = data;
      } finally {
        this.isLoading = false;
      }
    },
    openBook({ id }) {
      this.$router.push(`/book/${ id }`)
    }
  }
}
</script>
