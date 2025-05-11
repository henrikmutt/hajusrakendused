<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useForm, Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps({
  post: Object
});

const deletePost = () => {
  router.delete(route('posts.destroy', props.post.id), {
    onSuccess: () => {
      router.visit(route('posts.index'));
    },
  });
};

const deleteComment = (id: number) => {
  router.delete(route('comments.destroy', id));
};

const form = useForm({ content: '' });

const submit = () => {
  form.post(route('comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => form.reset()
  });
};
</script>

<template>
  <AppLayout>
    <div class="flex flex-col gap-6 w-full lg:w-1/2 mx-auto px-2 py-10">
      <div class="flex gap-4 justify-end">
        <Link :href="route('posts.edit', post.id)">
          <Button><Pencil /></Button>
        </Link>

        <Button variant="destructive" @click="deletePost"><Trash2 /></Button>
      </div>
      <h1 class="text-3xl font-bold">{{ post.title }}</h1>
      <p class="text-lg">{{ post.description }}</p>

      <div class="pt-8 border-t">
        <h2 class="text-xl font-semibold">Comments</h2>
        <ul class="space-y-4 my-4">
          <li v-for="comment in post.comments" :key="comment.id" class="border p-3 rounded">
            <p class="text-sm text-muted-foreground">{{ comment.user.name }}</p>
            <p>{{ comment.content }}</p>
            <div
                v-if="$page.props.auth.user?.is_admin || $page.props.auth.user?.id === comment.user_id"
                class="mt-2"
              >
                <Button @click="deleteComment(comment.id)" variant="destructive" size="sm">
                  <Trash2 />
                </Button>
            </div>
          </li>
        </ul>

        <div class="flex flex-col gap-6">
          <Input v-model="form.content" placeholder="Add a comment..." />
          <Button @click="submit" :disabled="form.processing">Comment</Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
