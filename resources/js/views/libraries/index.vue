<template>
  <div>
    <v-simple-table>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              Name
            </th>
            <th class="text-left">
              Books
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="library in libraries" :key="library.name" @click="openLibrary(library)">
            <td>{{ library.name }}</td>
            <td>{{ library.books_count }}</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <div class="text-center mt-2">
      <v-pagination :value="pagination.current_page" :length="pagination.last_page"
                    @input="getLibraries"
                    @next="getLibraries(pagination.current_page + 1)"
                    @previous="getLibraries(pagination.current_page - 1)"
      />
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      libraries: [],
      pagination: {}
    }
  },
  created() {
    this.getLibraries(1)
  },
  methods: {
    async getLibraries(page) {
      const { data: { data, ...pagination } } = await axios.get('/api/libraries', { params: { page } });
      this.libraries = data;
      this.pagination = pagination;
    },
    openLibrary({ id })
    {
      this.$router.push(`libraries/${id}`)
    }
  },
}
</script>
