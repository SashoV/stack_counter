<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

const props = defineProps({
    calculations: Object,
    sort: String,
});

const changeSort = (order) => {
    router.get('/dashboard/admin/calculations', { sort: order }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const showModal = ref(false);
const selectedCalculation = ref(null);

const openModal = (calculation) => {
    selectedCalculation.value = calculation;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedCalculation.value = null;
};

function downloadCSV(calculationId) {
    const url = `/calculations/${calculationId}/export`;
    const link = document.createElement('a');
    link.href = url;
    link.download = `calculation_${calculationId}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<template>
    <Head title="Admin Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Admin Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="container mx-auto py-4">
                            <h1 class="text-2xl font-bold mb-4">Book Stack Calculations (All users)</h1>
                            <!-- Sort Dropdown -->
                            <div class="mb-4">
                                <label for="sortSelect" class="block text-sm font-medium text-gray-700 mb-1">Sort
                                    by</label>
                                <select id="sortSelect"
                                    class="block w-48 px-4 py-2 border rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500"
                                    :value="props.sort" @change="changeSort($event.target.value)">
                                    <option value="desc">Newest First</option>
                                    <option value="asc">Oldest First</option>
                                </select>
                            </div>
                            <!-- Calculations Table -->
                            <div class="overflow-x-auto bg-white border rounded-lg">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-200 text-gray-700 text-left">
                                        <tr>
                                            <th class="py-3 px-6">#</th>
                                            <th class="py-3 px-6">Length</th>
                                            <th class="py-3 px-6">Visible</th>
                                            <th class="py-3 px-6">Calculation Time</th>
                                            <th class="py-3 px-6">Created By</th>
                                            <th class="py-3 px-6">Created At</th>
                                            <th class="py-3 px-6">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(calculation, index) in props.calculations.data"
                                            :key="calculation.id" class="border-b hover:bg-gray-100">
                                            <td class="py-3 px-6">{{ index + 1 }}</td>
                                            <td class="py-3 px-6">{{ calculation.length }}</td>
                                            <td class="py-3 px-6">{{ calculation.visible }}</td>
                                            <td class="py-3 px-6">{{ calculation.calculation_time }} ms</td>
                                            <td class="py-3 px-6">{{ calculation.user.name }}</td>
                                            <td class="py-3 px-6">{{ new
                                                Date(calculation.created_at).toLocaleString('en-GB')
                                                }}</td>
                                            <td class="py-3 px-6">
                                                <button @click="openModal(calculation)"
                                                    class="text-sky-600 hover:underline">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="props.calculations.data.length === 0">
                                            <td colspan="5" class="py-4 px-6 text-center text-gray-500">
                                                No calculations found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <Pagination :links="props.calculations.links" class="mt-4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="closeModal" maxWidth="5xl">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Calculation Details</h2>
                <div v-if="selectedCalculation">
                    <p><strong>Length:</strong> {{ selectedCalculation.length }}</p>
                    <p><strong>Visible:</strong> {{ selectedCalculation.visible }}</p>
                    <p><strong>Calculation Time:</strong> {{ selectedCalculation.calculation_time }} ms</p>
                    <p><strong>Created At:</strong> {{ new Date(selectedCalculation.created_at).toLocaleString('en-GB')
                    }}</p>
                    <!-- Download CSV button -->
                    <button @click="downloadCSV(selectedCalculation.id)" class="text-green-600 hover:underline"
                        title="Download CSV">
                        Download CSV
                    </button>
                </div>
                <!-- Matrix Display -->
                <div v-if="selectedCalculation?.matrix && selectedCalculation?.matrix.length" class="mt-6">
                    <h3 class="text-md font-semibold mb-2">Stacks</h3>
                    <div class="overflow-x-auto max-w-full">
                        <div class="inline-block min-w-max">
                            <!-- Column Headers -->
                            <div class="flex">
                                <!-- Empty corner cell -->
                                <div
                                    class="w-9 h-8 p-0 flex items-center justify-center border text-sm border-gray-300 bg-gray-100">
                                </div>
                                <!-- Column numbers -->
                                <div v-for="(col, colIndex) in selectedCalculation.matrix[0]"
                                    :key="'col-header-' + colIndex"
                                    class="w-9 h-8 p-0 flex items-center justify-center border text-sm text-gray-400 border-gray-300 bg-gray-100">
                                    {{ colIndex + 1 }}
                                </div>
                            </div>
                            <!-- Rows -->
                            <div v-for="(row, rowIndex) in selectedCalculation.matrix" :key="'row-' + rowIndex"
                                class="flex">
                                <!-- Row number -->
                                <div
                                    class="w-9 h-8 p-0 flex items-center justify-center border text-sm text-gray-400 border-gray-300 bg-gray-100">
                                    {{ rowIndex + 1 }}
                                </div>
                                <!-- Cells -->
                                <div v-for="(value, colIndex) in row" :key="'cell-' + rowIndex + '-' + colIndex"
                                    class="w-9 h-8 p-0 flex items-center justify-center border text-sm border-gray-300 bg-gray-50">
                                    {{ value }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button @click="closeModal" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
