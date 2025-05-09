<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { MapMouseEvent, Marker } from 'maplibre-gl';
import Radar from 'radar-sdk-js';
import 'radar-sdk-js/dist/radar.css';
import RadarMap from 'radar-sdk-js/dist/ui/RadarMap';
import { onMounted, ref } from 'vue';

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
const markers = ref<any[]>([]);
const selectedMarker = ref<any>(null);
const showDialog = ref(false);
const newMarker = ref({
    name: '',
    description: '',
    latitude: 0,
    longitude: 0,
});
const showEditDialog = ref(false);
const editableMarker = ref<any>(null);
const showAddDialog = ref(false);

const saveMarker = async () => {
    try {
        const res = await axios.post('/markers', {
            name: newMarker.value.name,
            description: newMarker.value.description,
            latitude: newMarker.value.latitude,
            longitude: newMarker.value.longitude,
        });

        const marker = res.data;
        markers.value.push(marker);

        if (typeof marker.latitude !== 'number' || typeof marker.longitude !== 'number' || isNaN(marker.latitude) || isNaN(marker.longitude)) {
            console.warn('Invalid coordinates, marker skipped');
            return;
        }

        const m = new Marker().setLngLat([marker.longitude, marker.latitude]).addTo(map.value!);

        m.getElement().addEventListener('click', () => {
            selectedMarker.value = marker;
            showDialog.value = true;
        });

        newMarker.value.name = '';
        newMarker.value.description = '';
        showAddDialog.value = false;
    } catch (err) {
        console.error('Failed to save marker:', err);
    }
};

const openEditDialog = () => {
    editableMarker.value = { ...selectedMarker.value };
    showDialog.value = false;
    showAddDialog.value = false;
    showEditDialog.value = true;
};

const deleteMarker = async () => {
    if (!selectedMarker.value?.id) return;

    try {
        await axios.delete(`/markers/${selectedMarker.value.id}`);
        markers.value = markers.value.filter((m) => m.id !== selectedMarker.value.id);
        showDialog.value = false;
    } catch (err) {
        console.error('Failed to delete marker:', err);
    }
};

const updateMarker = async () => {
    if (!editableMarker.value?.id) return;

    try {
        const res = await axios.put(`/markers/${editableMarker.value.id}`, {
            name: editableMarker.value.name,
            description: editableMarker.value.description,
        });

        const updated = res.data;

        const index = markers.value.findIndex((m) => m.id === updated.id);
        if (index !== -1) {
            markers.value[index] = updated;
        }

        showEditDialog.value = false;

        window.location.reload();
    } catch (err) {
        console.error('Failed to update marker:', err);
    }
};

Radar.initialize('prj_test_pk_f4e1b950a3bf76dfdef836293d5c0dcae8980fba');

onMounted(async () => {
    const radarMap = Radar.ui.map({
        container: 'map',
        style: 'radar-default-v1',
        center: [24.714291, 59.377895],
        zoom: 14,
    });

    map.value = radarMap;

    try {
        const res = await axios.get('/markers');
        markers.value = res.data;

        markers.value.forEach((marker) => {
            const m = new Marker().setLngLat([marker.longitude, marker.latitude]).addTo(radarMap);

            m.getElement().addEventListener('click', () => {
                showAddDialog.value = false;
                selectedMarker.value = marker;
                showDialog.value = true;
            });
        });
    } catch (err) {
        console.error('Failed to load markers:', err);
    }

    radarMap.on('click', (e) => {
        mapMouseEv.value = e;
        newMarker.value.latitude = e.lngLat.lat;
        newMarker.value.longitude = e.lngLat.lng;
        showAddDialog.value = true;
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

                <div
                    class="relative flex aspect-video justify-center overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <!-- <a href="/posts" class="flex items-center justify-center text-white bg-black w-full h-full text-4xl">Posts</a> -->
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <a href="/products" class="flex h-full w-full items-center justify-center bg-black text-4xl text-white">Epood</a>
                </div>
            </div>

            <div
                class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min"
            >
                <div id="map" class="h-full w-full"></div>
            </div>
        </div>

        <Dialog v-model:open="showAddDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add Marker</DialogTitle>
                    <DialogDescription>Save a new marker at the selected location.</DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div>
                        <label class="text-sm font-medium">Name</label>
                        <input v-model="newMarker.name" class="w-full rounded border p-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Description</label>
                        <textarea v-model="newMarker.description" class="w-full rounded border p-2"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Latitude</label>
                        <input :value="newMarker.latitude" disabled class="w-full rounded border bg-gray-100 p-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Longitude</label>
                        <input :value="newMarker.longitude" disabled class="w-full rounded border bg-gray-100 p-2" />
                    </div>
                </div>

                <DialogFooter>
                    <button @click="saveMarker" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save Marker</button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <Dialog v-model:open="showDialog">
            <DialogTrigger class="hidden">Open</DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ selectedMarker?.name }}</DialogTitle>
                    <DialogDescription>
                        {{ selectedMarker?.description }}
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">Coordinates: {{ selectedMarker?.latitude }}, {{ selectedMarker?.longitude }}</p>
                    <div class="flex gap-2">
                        <button class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600" @click="openEditDialog">Edit</button>
                        <button class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700" @click="deleteMarker">Delete</button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <Dialog v-model:open="showEditDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit Marker</DialogTitle>
                    <DialogDescription>Update marker details.</DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div>
                        <label class="text-sm font-medium">Name</label>
                        <input v-model="editableMarker.name" class="w-full rounded border p-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Description</label>
                        <textarea v-model="editableMarker.description" class="w-full rounded border p-2"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Latitude</label>
                        <input :value="editableMarker.latitude" disabled class="w-full rounded border bg-gray-100 p-2" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Longitude</label>
                        <input :value="editableMarker.longitude" disabled class="w-full rounded border bg-gray-100 p-2" />
                    </div>
                </div>

                <DialogFooter>
                    <button @click="updateMarker" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Save Changes</button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
