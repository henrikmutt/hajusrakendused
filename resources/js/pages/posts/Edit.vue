<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps({ post: Object });

const form = useForm({
  title: props.post.title,
  description: props.post.description,
});

const submit = () => {
  form.put(route('posts.update', props.post.id));
};
</script>

<template>
  <AppLayout>
    <div class="w-full lg:w-1/2 px-2 mx-auto py-10">
      <Card>
        <CardHeader>
          <CardTitle>Edit Post</CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <Input v-model="form.title" placeholder="Title" />
          <Textarea v-model="form.description" rows="6" />
          <Button @click="submit" :disabled="form.processing">Update</Button>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
