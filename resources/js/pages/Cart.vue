<script setup lang="ts">
import { computed } from 'vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import Button from '@/components/ui/button/Button.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Input from '@/components/ui/input/Input.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  cart: Object
});


const formatCurrency =(amount: number) => {
   return new Intl.NumberFormat("et", { style: "currency", currency: "EUR" }).format(amount);
}

const updateCart = (id: number, quantity:number | string) => {
    router.post(route('cart.update'), {
        id: id,
        quantity: quantity,
    });
};
</script>


<template>
    <AppLayout>
        <div class="w-full px-6 mx-auto mt-10 space-y-4">
          <Card v-for="(item, id) in $page.props.cart" :key="id" class="px-6 py-2 gap-4 flex flex-col md:flex-row md:justify-between">
            <div class="flex">
                <img :src="item.image" class="md:w-16 md:h-16">
                <CardHeader>
                    <CardTitle>{{ item.name }}</CardTitle>
                  <CardDescription>{{ item.description }}</CardDescription>
                </CardHeader>
            </div>
            <CardContent class="flex justify-between p-0 items-center">
              <div class="flex flex-col gap-2">
                <p class="text-sm"> {{ formatCurrency(item.price) }} </p>
                <Input type="number" @update:model-value="(value) => updateCart(id, value)" :model-value="item.quantity" min="0" class="text-sm" />
              </div>
            </CardContent>
            <div class="font-semibold md:text-right">
              {{ formatCurrency(item.price * item.quantity) }}
            </div>
          </Card>
      
          <Separator />
      
          <div class="flex justify-between text-lg font-semibold px-4">
            <span>Total:</span>
            <span>{{ formatCurrency($page.props.cartTotal) }}</span>

          </div>
          
          <Button class="w-full md:w-auto">Proceed to Checkout</Button>
        
        </div>
    </AppLayout>
</template>
