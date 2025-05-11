<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps({
  posts: Array
});
</script>

<template>
  <AppLayout>
    <div class="px-2 lg:px-6 py-10 space-y-4">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Blog Posts</h1>
        <Link :href="route('posts.create')">
          <Button>Create Post</Button>
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Card v-for="post in posts" :key="post.id">
          <CardHeader>
            <CardTitle class="text-lg">{{ post.title }}</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="line-clamp-3">{{ post.description }}</p>
            <Link :href="route('posts.show', post.id)">
              <Button variant="link" class="px-0">More</Button>
            </Link>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
