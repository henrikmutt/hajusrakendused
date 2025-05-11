<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ChevronRight } from 'lucide-vue-next';

const form = useForm({
  title: '',
  image: '',
  description: '',
  blue_corner: '',
  red_corner: ''
});

const submit = () => {
  form.post(route('fight-cards.store'), {
    onSuccess: () => form.reset()
  });
};
</script>

<template>
  <AppLayout>
    <div class="flex flex-col gap-6 w-full lg:w-1/2 px-2 mx-auto py-10">
      <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
        <Button><a href="https://hajus.ta23mutt.itmajakas.ee/fight-cards/07b4cc44-9042-4944-b1dc-56eac757ca4f" target="_blank" class="flex gap-4 items-center">My API <ChevronRight /></a></Button>
        <Button :as="Link" :href="route('fightCards.Classmate')">Classmate API <ChevronRight /></Button>
      </div>
      <Card class="flex flex-col gap-6">
        <CardHeader>
          <CardTitle>Add Fight Card</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-6">
          <Input v-model="form.title" placeholder="Title" />
          <Input v-model="form.image" placeholder="Image URL" />
          <Textarea v-model="form.description" placeholder="Description" rows="4" />
          <Input v-model="form.blue_corner" placeholder="Blue Corner" class="bg-blue-300 font-bold"/>
          <Input v-model="form.red_corner" placeholder="Red Corner" class="bg-red-300 font-bold"/>
          <Button @click="submit" :disabled="form.processing">Submit</Button>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
