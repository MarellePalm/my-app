<script setup lang="ts">
import { ref, onMounted } from "vue"
import L from "leaflet"
import "leaflet/dist/leaflet.css"
import { Dialog,DialogContent,DialogHeader,DialogTitle,DialogDescription } from "./ui/dialog";
import { Form } from "@inertiajs/vue3";
import { Label } from "./ui/label";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Button } from "./ui/button";

const mapEl = ref(null)
const selectedLocation = ref<{ lat: number; lng: number }>();

const mapClick= (e:PointerEvent)=> {
    selectedLocation.value = {
        lat: e.latlng.lat,
        lng: e.latlng.lng
    
    }
    

};

const props = defineProps<{
  markers: {
    id: number
    name: string
    lat: number
    lng: number
    description: string | null
    added: string | null
    edited: string | null
  }[]
}>()

onMounted(() => {
  const map = L.map(mapEl.value, {
    zoomControl: true
  }).setView([59.4, 24.7], 8)

  L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
  ).addTo(map)

  map.on('click', mapClick);

  props.markers.forEach((marker) => {
  L.marker([marker.lat, marker.lng])
    .addTo(map)
    .bindPopup(`<b>${marker.name}</b><br>${marker.description ?? ""}`)
})
});



</script>

<template>
  <div ref="mapEl" class="z-10 h-full"></div>
  <Dialog :open="!!selectedLocation" @update:open="selectedLocation=undefined">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Salvesta uus marker</DialogTitle>
        <DialogDescription>
          Lisa nimi ja kirjeldus
        </DialogDescription>
        <Form method="post" action="/markers">
           <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <Label class="mb-1.5" for="name">Name</Label>
                <Input name="name"/>
            </div>
            <div>
                <Label class="mb-1.5" for="lat">Lat</Label>
                <Input id="lat" name="lat" disabled :default-value="selectedLocation?.lat"/>
            </div>
            <div>
                <Label class="mb-1.5" for="lng">Lng</Label>
                <Input id="lng" name="lng" disabled :default-value="selectedLocation?.lng"/>
            </div>
            <input type="hidden" name="lat" :value="selectedLocation?.lat" />
            <input type="hidden" name="lng" :value="selectedLocation?.lng" />
            <Textarea name="description" class="col-span-2"></Textarea>
            <Button class="col-span-2" type="submit"> Salvesta </Button>
           </div>
        </Form>
      </DialogHeader>
    </DialogContent>
  </Dialog>
  
</template>



