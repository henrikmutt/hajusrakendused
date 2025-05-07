<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Radar from 'radar-sdk-js';
import 'radar-sdk-js/dist/radar.css';
import { onMounted } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { MapMouseEvent } from 'maplibre-gl';
import { ref } from 'vue';
import RadarMap from 'radar-sdk-js/dist/ui/RadarMap';

const props = defineProps<{
    weather: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const show = ref(false);
const map = ref<RadarMap>();

const mapMouseEv = ref<MapMouseEvent>();

Radar.initialize('prj_test_pk_f4e1b950a3bf76dfdef836293d5c0dcae8980fba');

onMounted(() => {
    const map = Radar.ui.map({
        container: 'map',
        style: 'radar-default-v1',
        center: [24.714291, 59.377895],
        zoom: 14,
    });
    map.on('click', (e) => {
        mapMouseEv.value = e;
        
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                
                <!-- Weather Card -->
                <div class="relative aspect-video overflow-scroll rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Card>
                        <CardHeader>
                            <CardTitle>Weather in {{ weather.name }}</CardTitle>
                            <CardDescription>{{ weather.weather[0].description }}</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <p><strong>Temperature:</strong> {{ weather.main.temp }} °C</p>
                            <p><strong>Feels Like:</strong> {{ weather.main.feels_like }} °C</p>
                            <p><strong>Wind:</strong> {{ weather.wind.speed }} m/s</p>
                            <p><strong>Humidity:</strong> {{ weather.main.humidity }}%</p>
                        </CardContent>
                        <CardFooter> Last updated: cached for 1 hour </CardFooter>
                    </Card>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>

            <div
                class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min"
            >
                <div id="map" class="h-full w-full"></div>
            </div>
        </div>
<Dialog>
  <DialogTrigger>Open</DialogTrigger>
  <DialogContent>
    <DialogHeader>
      <DialogTitle>Koordinaadid</DialogTitle>
      <DialogDescription>
        {{ mapMouseEv?.lngLat }}
      </DialogDescription>
    </DialogHeader>
  </DialogContent>
</Dialog>

</AppLayout>
</template>
