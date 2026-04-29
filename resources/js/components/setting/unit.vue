<template>
    <div class="card shadow-sm border-0 mx-auto rounded-4 overflow-hidden" style="max-width: 800px;">
        <form @submit.prevent="submitForm">
            <!-- HEADER INFO -->
            <div class="p-3 border-bottom bg-white">
                <div class="row g-3">
                    <!-- Flex container to keep Date and Unit on the same line -->
                    <div class="col-12 d-flex flex-wrap gap-3">
                        <div class="input-group input-group-sm w-auto shadow-sm">
                            <span class="input-group-text bg-light fw-bold border-end-0 text-secondary">Date</span>
                            <input v-model="formData.report_date" type="date" class="form-control border-start-0">
                        </div>

                        <div class="input-group input-group-sm w-auto shadow-sm">
                            <span class="input-group-text bg-light fw-bold border-end-0 text-secondary">Unit</span>
                            <input v-model="formData.unit_name" type="text" class="form-control border-start-0"
                                placeholder="Enter Unit...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE AREA -->
            <div ref="scrollArea" class="main-scroll-area" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" style="text-align: center;">
                    <thead class="sticky-top bg-light">
                        <tr class="small text-uppercase text-muted border-bottom">
                            <th class="text-center py-2" style="width: 60px;">SL</th>
                            <th class="py-2">Line No</th>
                            <th class="py-2">Remarks</th>
                            <th class="text-center py-2" style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in formData.outputRows" :key="'out-' + index" class="border-bottom-0">
                            <td class="bg-light-subtle text-center fw-bold text-secondary small">{{ index + 1 }}</td>
                            <td class="p-0">
                                <input v-model="row.line_no" class="grid-input" placeholder="Enter line..."
                                    @focus="index === formData.outputRows.length - 1 && addOutputRow()">
                            </td>
                            <td class="p-0">
                                <input v-model="row.remarks" class="grid-input" placeholder=" ">
                            </td>
                            <td class="text-center p-0">
                                <button v-if="formData.outputRows.length > 1" type="button"
                                    class="btn btn-link text-danger text-decoration-none fw-bold"
                                    @click="removeRow(index)">&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER ACTION -->
            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="small text-muted fw-medium">{{ formData.outputRows.length }} Row(s)</span>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                report_date: new Date().toISOString().substr(0, 10),
                unit_name: '', // Added this back
                outputRows: [{ line_no: '', remarks: '' }]
            }
        };
    },
    methods: {
        addOutputRow() {
            this.formData.outputRows.push({ line_no: '', remarks: '' });
        },
        removeRow(index) {
            if (this.formData.outputRows.length > 1) {
                this.formData.outputRows.splice(index, 1);
            }
        },
        resetForm() {
            this.formData.unit_name = '';
            this.formData.outputRows = [{ line_no: '', remarks: '' }];
            this.formData.report_date = new Date().toISOString().substr(0, 10);
        },
        async submitForm() {
            try {
                await axios.post(' api/unit/add', this.formData);
                alert("Data Saved Successfully!");
                this.resetForm();
            } catch (err) {
                if (err.response && err.response.status === 422) {
                    alert("Validation Failed!");
                } else {
                    console.error("Save Error:", err);
                    alert("Save Failed!");
                }
            }
        }
    }
};
</script>

<style scoped>
.grid-input {
    width: 100%;
    border: none;
    padding: 12px;
    font-size: 14px;
    outline: none;
    background: transparent;
    transition: background 0.2s;
}

.grid-input:focus {
    background: #f8fbff;
}

/* Custom scrollbar for the scroll area */
.main-scroll-area::-webkit-scrollbar {
    width: 6px;
}

.main-scroll-area::-webkit-scrollbar-thumb {
    background-color: #e0e0e0;
    border-radius: 10px;
}

.table th {
    font-weight: 700;
    letter-spacing: 0.5px;
}
</style>
