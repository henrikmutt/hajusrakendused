<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const form = useForm({
  title: '',
  description: ''
});

const submit = () => {
  form.post(route('posts.store'));
};
</script>

<template>
  <AppLayout>
    <div class="w-full lg:w-1/2 px-2 mx-auto py-10">
      <Card class="flex flex-col gap-6">
        <CardHeader>
          <CardTitle>Write your post</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-6">
          <Input v-model="form.title" placeholder="Title" />
          <Textarea v-model="form.description" placeholder="Write your post..." rows="6" />
          <Button @click="submit" :disabled="form.processing">Submit</Button>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
