<template>
    <div v-if="src" class="fixed inset-0 bg-black/75 flex items-center justify-center z-50" @click.self="close">
        <div class="relative max-w-4xl max-h-full w-full h-full p-4">
            <button @click="close" class="absolute top-4 right-4 text-white text-4xl">&times;</button>
            <iframe v-if="isPdf" :src="src" class="w-full h-full" frameborder="0"></iframe>
            <img v-else :src="src" class="w-full h-full object-contain" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['close']);

const isPdf = computed(() => {
    return props.src.toLowerCase().endsWith('.pdf');
});

const close = () => {
    emit('close');
};
</script>
