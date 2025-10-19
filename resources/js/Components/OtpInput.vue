<template>
    <div class="flex justify-center space-x-2">
        <input
            v-for="(n, index) in 6"
            :key="index"
            class="w-12 h-12 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            type="text"
            maxlength="1"
            pattern="[0-9]"
            :ref="el => { if (el) inputs[index] = el }"
            @input="handleInput(index, $event)"
            @keydown.delete="handleDelete(index, $event)"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const emit = defineEmits(['update:modelValue']);

const inputs = ref([]);
const otp = ref([]);

onMounted(() => {
    inputs.value[0].focus();
});

const handleInput = (index, event) => {
    const value = event.target.value;
    otp.value[index] = value;
    if (value && index < 5) {
        inputs.value[index + 1].focus();
    }
    emit('update:modelValue', otp.value.join(''));
};

const handleDelete = (index, event) => {
    if (event.target.value === '' && index > 0) {
        inputs.value[index - 1].focus();
    }
};
</script>
