<template>
    <div class="flex items-center justify-between">
        <div v-for="(step, index) in steps" :key="index" class="flex-1">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold" :class="getStepClass(index)">
                    <span v-if="index < currentStep">✓</span>
                    <span v-else>{{ index + 1 }}</span>
                </div>
                <div v-if="index < steps.length - 1" class="flex-1 h-1 mx-2" :class="getLineClass(index)"></div>
            </div>
            <p class="text-sm font-medium mt-2" :class="getTextClass(index)">{{ step }}</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    steps: {
        type: Array,
        required: true,
    },
    currentStep: {
        type: Number,
        required: true,
    },
});

const getStepClass = (index) => {
    if (index < props.currentStep) {
        return 'bg-green-600 text-white';
    }
    if (index === props.currentStep) {
        return 'bg-indigo-600 text-white';
    }
    return 'bg-gray-300 text-gray-600';
};

const getLineClass = (index) => {
    if (index < props.currentStep) {
        return 'bg-green-600';
    }
    if (index === props.currentStep) {
        return 'bg-indigo-600';
    }
    return 'bg-gray-300';
};

const getTextClass = (index) => {
    if (index < props.currentStep) {
        return 'text-green-600';
    }
    if (index === props.currentStep) {
        return 'text-indigo-600';
    }
    return 'text-gray-500';
};
</script>
