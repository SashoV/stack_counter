<template>
    <form class="my-4 mx-3 py-6 px-4 bg-white shadow-sm sm:rounded-lg min-h-64" @submit.prevent="submit">
        <div class="flex justify-center items-end">
            <!-- Length Input -->
            <div>
                <label class="block text-sm font-bold mb-2">
                    Enter side length of the marked area(0-50):
                </label>
                <input v-model.number="form.length" type="number" class="w-24 text-sm border rounded px-2 py-1" min="1"
                    max="50" />
            </div>
            <div>
                <!-- Submit Button -->
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Submit
                </button>
            </div>
        </div>
        <div v-if="isValidLength" class="mb-4 mt-6 flex flex-col justify-center items-center">
            <div class="text-center">
                All fields must be filled with numbers from 0 to 1000.
            </div>
            <div class="overflow-x-auto max-w-full border rounded bg-white p-2">
                <div class="inline-block min-w-max">
                    <!-- Column headers -->
                    <div class="flex border-b border-gray-300">
                        <!-- Empty top-left cell for alignment -->
                        <div class="w-8 border-r border-gray-300 bg-gray-100"></div>
                        <div v-for="(col, colIndex) in matrix[0]" :key="'col-header-' + colIndex"
                            class="w-9 h-8 flex items-center justify-center border border-gray-300 bg-gray-100 text-sm font-semibold text-center select-none">
                            {{ colIndex + 1 }}
                        </div>
                    </div>
                    <!-- Matrix rows -->
                    <div v-for="(row, rowIndex) in matrix" :key="'row-' + rowIndex"
                        class="flex border-b last:border-b-0 border-gray-300">
                        <!-- Row number -->
                        <div class="flex items-center justify-center w-8 border-r border-gray-300 bg-gray-100 text-sm font-semibold text-center select-none"
                            style="min-width: 32px;">
                            {{ rowIndex + 1 }}
                        </div>
                        <!-- Matrix cells -->
                        <input v-for="(value, colIndex) in row" :key="'cell-' + rowIndex + '-' + colIndex"
                            v-model="matrix[rowIndex][colIndex]" type="number" class="w-9 h-8 p-0 text-sm border border-gray-300 text-center no-spinner appearance-none
                    [&::-webkit-inner-spin-button]:appearance-none
                    [&::-webkit-outer-spin-button]:appearance-none" :class="{
                        'border-red-500': matrixErrors[rowIndex]?.[colIndex]
                    }" />
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="form.length !== '' && !isValidLength" class="text-red-600 text-sm mt-4 text-center">
            Please enter a number between 1 and 50.
        </div>
    </form>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    length: '',
    matrix: [],
})
const matrixErrors = ref([]);
const matrix = ref([])

const isValidLength = computed(() => {
    const size = parseInt(form.length)
    return !isNaN(size) && size >= 1 && size <= 50
})

// Watch for length changes and rebuild matrix
watch(
    () => form.length,
    (newLength) => {
        matrixErrors.value = []
        form.clearErrors('matrix')
        const size = parseInt(newLength)
        if (!isNaN(size) && size > 0) {
            matrix.value = Array.from({ length: size }, () =>
                Array.from({ length: size }, () => '')
            )
        } else {
            matrix.value = []
            form.matrix = []
            matrixErrors.value = []
        }
    }
)

// Submit the form with matrix data
function submit() {
    form.matrix = matrix.value
    console.log('Submitted matrix:', matrix.value)
    console.log('Length:', form.length)
    form.post('/dashboard/calculations', {
        preserveScroll: true,
        data: {
            length: form.length,
            matrix: matrix.value,
        },
        onSuccess: () => {
            console.log('Form submitted successfully')
        },
        onError: (errors) => {
            console.error('Form submission error:', errors)
            matrixErrors.value = []

            Object.keys(form.errors).forEach(key => {
                const match = key.match(/^matrix\.(\d+)\.(\d+)$/)
                if (match) {
                    const row = parseInt(match[1])
                    const col = parseInt(match[2])
                    if (!matrixErrors.value[row]) {
                        matrixErrors.value[row] = []
                    }
                    matrixErrors.value[row][col] = true
                }
            })
        },
    })
}
</script>

<style scoped>
/* Firefox */
input.no-spinner {
    -moz-appearance: textfield;
}
</style>
