<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

defineProps<{
  products: {
    id: number;
    title: string;
    description: string;
    image: string;
    price: number;
  }[]
}>();

const addToCart = (product: any) => {
  router.post(route('cart.add', product), undefined, {
    preserveScroll: true
  })
};
</script>

<template>
    <AppLayout>
      <div class="p-6 flex justify-end">
        {{ $page.props.cart }}
        <Button variant="outline">
          <ShoppingCart class="size-5" />
          <div>{{ Object.entries($page.props.cart ?? {})?.length }}</div>
        </Button>
      </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-4">
          <Card v-for="product in products" :key="product.id" class="w-full">
            <CardHeader>
              <img :src="product.image" :alt="product.title" class="w-full object-cover rounded-xl" />
              <CardTitle class="mt-2">{{ product.title }}</CardTitle>
              <CardDescription>{{ product.description }}</CardDescription>
            </CardHeader>
            <CardContent>
              {{ product.price }} €
            </CardContent>
            <CardFooter>
              
              <Button class="w-full" @click="addToCart(product)">Add to cart</Button>
            </CardFooter>
          </Card>
        </div>
    </AppLayout>
</template>
