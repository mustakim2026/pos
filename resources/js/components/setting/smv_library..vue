<template>
    <div class="card shadow-sm border-0 mx-auto rounded-4 overflow-hidden" style="max-width:80%;">
        <form @submit.prevent="submitForm">
            <!-- TABLE AREA -->
            <div ref="scrollArea" class="main-scroll-area" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" style="text-align: center;">
                    <thead class="sticky-top bg-light">
                        <tr class="small text-uppercase text-muted border-bottom">
                            <th class="text-center py-2" style="width: 60px;">SL</th>
                            <th class="py-2">Po No</th>
                            <th class="py-2">Style/Model</th>
                            <th class="py-2">Item</th>
                            <th class="py-2">SMV</th>
                            <th class="py-2">CM(DZ)</th>
                            <th class="py-2">CM(Pcs)</th>
                            <th class="py-2">FOB(Pcs)</th>
                            <th class="py-2">Remarks</th>
                            <th class="text-center py-2" style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in formData.smvRows" :key="'out-' + index" class="border-bottom-0">
                            <td class="bg-light-subtle text-center fw-bold text-secondary small">{{ index + 1 }}</td>
                            <td class="p-0">
                                <input v-model="row.po_no" class="grid-input" placeholder="Enter line..."
                                    @focus="index === formData.smvRows.length - 1 && addOutputRow()">
                            </td>
                            <td class="p-0"><input v-model="row.style_model_no" class="grid-input"></td>
                            <td class="p-0"><input v-model="row.item" class="grid-input"></td>

                            <!-- Added missing SMV input -->
                            <td class="p-0"><input v-model="row.smv" class="grid-input" type="number" step="0.01"></td>

                            <td class="p-0"><input v-model="row.CM_Dz" class="grid-input"></td>
                            <td class="p-0"><input v-model="row.CM_pcs" class="grid-input"></td>
                            <td class="p-0"><input v-model="row.fob_pcs" class="grid-input"></td>
                            <td class="p-0"><input v-model="row.remarks" class="grid-input"></td>
                            <td class="text-center p-0">
                                <button v-if="formData.smvRows.length > 1" type="button" @click="removeRow(index)"
                                    class="btn text-danger">&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER ACTION -->
            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="small text-muted fw-medium">{{ formData.smvRows.length }} Row(s)</span>
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
                smvRows: [{ po_no: '', style_model_no: '', item: '', smv: '', CM_Dz: '', CM_pcs: '', fob_pcs: '', remarks: '' }]
            }
        };
    },
    methods: {
        addOutputRow() {
            this.formData.smvRows.push({ po_no: '', style_model_no: '', item: '', smv: 0, CM_Dz: 0, CM_pcs: 0, fob_pcs: 0, remarks: '' });
        },
        removeRow(index) {
            if (this.formData.smvRows.length > 1) {
                this.formData.smvRows.splice(index, 1);
            }
        },
        resetForm() {
            this.formData.smvRows = [{ po_no: '', style_model_no: '', item: '', smv: '', CM_Dz: '', CM_pcs: '', fob_pcs: '', remarks: '' }];

        },
        async submitForm() {
            try {
                await axios.post('/api/smv/add', this.formData);
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

.table>:not(caption)>*>* {
    padding: .1rem .1rem;

}
</style>
