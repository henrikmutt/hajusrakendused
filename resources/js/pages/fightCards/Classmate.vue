<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const classmateData = ref([]);
const isLoading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const res = await fetch('/fight-cards/classmate-data');
    const data = await res.json();
    classmateData.value = data;
  } catch (err) {
    console.error(err);
    error.value = 'Failed to load data.';
  } finally {
    isLoading.value = false;
  }
});
</script>

<template>
  <AppLayout>
    <div class="py-10 px-6 space-y-6">
      <h1 class="text-3xl font-bold">Classmate API</h1>

      <div v-if="isLoading">Loading...</div>
      <div v-else-if="error">{{ error }}</div>

      <div v-else class="grid gap-4 md:grid-cols-2">
        <Card v-for="item in classmateData" :key="item.id">
          <CardHeader>
            <CardTitle>{{ item.title }}</CardTitle>
          </CardHeader>
          <CardContent class="space-y-2">
            <img :src="item.image" alt="Cover image" class="rounded w-full h-48 object-cover" />
            <p>{{ item.description }}</p>
            <p><strong>Developer:</strong> {{ item.developer }}</p>
            <p><strong>Genres:</strong> {{ item.genres ? item.genres.join(', ') : '—' }}</p>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
