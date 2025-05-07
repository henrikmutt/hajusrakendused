<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref<any[]>([]);

onMounted(async () => {
  try {
    const res = await axios.get('/posts');
    posts.value = res.data;
  } catch (err) {
    console.error('Failed to load posts:', err);
  }
});
</script>

<template>
  <div class="p-6 space-y-4">
    <h1 class="text-2xl font-semibold">Blog Posts</h1>

    <button
      class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      @click="/* open add post form */"
    >
      Add New Post
    </button>

    <div v-if="posts.length === 0" class="text-muted">No posts found.</div>

    <div v-else class="space-y-2">
      <div
        v-for="post in posts"
        :key="post.id"
        class="border rounded-lg p-4 flex justify-between items-center"
      >
        <div>
          <h2 class="text-lg font-semibold">{{ post.title }}</h2>
          <p class="text-sm text-muted-foreground">{{ new Date(post.created_at).toLocaleString() }}</p>
        </div>
        <div class="flex gap-2">
          <button
            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
            @click="/* open edit form */"
          >
            Edit
          </button>
          <button
            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
            @click="/* call delete */"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
