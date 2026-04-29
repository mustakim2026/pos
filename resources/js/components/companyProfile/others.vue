<template>
    <div class="card shadow border-0">
        <form @submit.prevent="submitForm">
            <div class="card-body p-0">
                <!-- 1. HEADER INFO -->
                <div class="p-3 border-bottom bg-white">
                    <div class="row align-items-center g-3">
                        <div class="col-md-8 d-flex gap-3">
                            <div class="input-group input-group-sm w-auto">
                                <span class="input-group-text bg-light fw-bold">Date</span>
                                <input v-model="formData.report_date" type="date" class="form-control">
                            </div>
                            <div class="input-group input-group-sm w-auto">
                                <span class="input-group-text bg-light fw-bold">Unit</span>
                                <input v-model="formData.unit_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-inline-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                                <label class="small fw-bold mb-0 me-2">Observer:</label>
                                <input v-model="formData.observer" type="text"
                                    class="form-control form-control-sm border-0 bg-transparent p-0"
                                    placeholder="Name...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. TABS -->
                <div class="bg-white border-bottom">
                    <ul class="nav nav-tabs border-0">
                        <li class="nav-item">
                            <button type="button" class="nav-link px-4 py-2 border-0"
                                :class="{ 'active bg-primary text-white': activeTab === 1 }"
                                @click="activeTab = 1">Daily Output</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link px-4 py-2 border-0"
                                :class="{ 'active bg-primary text-white': activeTab === 2 }"
                                @click="activeTab = 2">Daily Attendance</button>
                        </li>
                    </ul>
                </div>

                <!-- 3. SCROLL AREA -->
                <div class="main-scroll-area" ref="scrollContainer" style="max-height: 500px; overflow-y: auto;">

                    <!-- TAB 1: DAILY OUTPUT -->
                    <table v-if="activeTab === 1" class="table table-bordered align-middle text-center mb-0">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>#</th>
                                <th>Line</th>
                                <th>Po No</th>
                                <th>Style/Model</th>
                                <th>Item</th>
                                <th>Target</th>
                                <th>Output</th>
                                <th>Remarks</th>
                                <th style="width: 50px;">Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in formData.outputRows" :key="'out-' + index">
                                <td>{{ index + 1 }}</td>
                                <td><input v-model="row.line_no" type="text" class="grid-input"
                                        @focus="index === formData.outputRows.length - 1 ? addOutputRow() : null"></td>
                                <td><input v-model="row.po_no" type="text" class="grid-input"></td>
                                <td><input v-model="row.style_model_no" type="text" class="grid-input"></td>
                                <td><input v-model="row.item" type="text" class="grid-input"></td>
                                <td><input v-model.number="row.target" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.output" type="number" class="grid-input"></td>
                                <td><input v-model="row.remarks" type="text" class="grid-input"></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger"
                                        @click="confirmDelete('outputRows', index)">&times;</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- TAB 2: DAILY ATTENDANCE (Example Structure) -->
                    <div class="main-scroll-area" ref="scrollContainer" style="max-height: 500px; overflow-y: auto;">
                        <!-- TAB 1: OUTPUT -->
                        <table v-if="activeTab === 1" class="table table-bordered align-middle text-center mb-0">
                            <thead class="sticky-top bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Line</th>
                                    <th>Po No</th>
                                    <th>Style/Model</th>
                                    <th>Target</th>
                                    <th>Output</th>
                                    <th>Remarks</th>
                                    <th style="width: 50px;">Del</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in outputRows" :key="'out-' + index">
                                    <td>{{ index + 1 }}</td>
                                    <td><input v-model="row.line" type="text" class="grid-input"
                                            @focus="index === outputRows.length - 1 ? addOutputRow() : null"></td>
                                    <td><input v-model="row.poNO" type="text" class="grid-input"></td>
                                    <td><input v-model="row.styleModelNO" type="text" class="grid-input"></td>
                                    <td><input v-model.number="row.target" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.output" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.remarks" type="number" class="grid-input"></td>
                                    <td><input type="checkbox" @change="confirmDelete(index)"></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- TAB 2: ATTENDANCE -->
                        <table v-if="activeTab === 2"
                            class="table table-bordered align-middle text-center mb-0 custom-grid-table">
                            <thead class="sticky-top bg-light">
                                <tr class="small fw-bold">
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Line Name</th>
                                    <th rowspan="2">Po No</th>
                                    <th rowspan="2">Style/Model</th>
                                    <th colspan="2">8:00-1:00</th>
                                    <th colspan="2">2:00-5:00</th>
                                    <th colspan="2">5:00-6:00</th>
                                    <th colspan="2">6:00-7:00</th>
                                    <th colspan="2">7:00-8:00</th>
                                    <th colspan="2">8:00-9:00</th>
                                    <th colspan="2">9:00-10:00</th>
                                    <th colspan="2">10></th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">Del</th>
                                </tr>
                                <tr class="small fw-bold">
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>OP</th>
                                    <th>HP</th>
                                    <th>Workig/Hrs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in attendanceRows" :key="'att-' + index">
                                    <td>{{ index + 1 }}</td>
                                    <td><input v-model="row.lineName" type="text" class="grid-input"
                                            @focus="index === attendanceRows.length - 1 ? addAttendanceRow() : null">
                                    </td>
                                    <td><input v-model.number="row.bOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.bHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aOP" type="number" class="grid-input"></td>
                                    <td><input v-model.number="row.aHP" type="number" class="grid-input"></td>
                                    <td><input type="checkbox" @change="confirmDelete(index)"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- 4. FOOTER ACTION -->
                <div class="p-3 border-top bg-light text-end">
                    <button type="submit" class="btn btn-primary px-5">Submit Report</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            activeTab: 1,
            formData: {
                report_date: new Date().toISOString().substr(0, 10),
                unit_name: '',
                observer: '',
                outputRows: [
                    { line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0, remarks: '' }
                ],
                attendanceRows: [
                    { dept: '', total: 0, present: 0 }
                ]
            }
        }
    },
    methods: {
        addOutputRow() {
            this.formData.outputRows.push({
                line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0, remarks: ''
            });
        },
        addAttendanceRow() {
            this.formData.attendanceRows.push({
                dept: '', total: 0, present: 0
            });
        },
        confirmDelete(arrayName, index) {
            if (this.formData[arrayName].length > 1) {
                this.formData[arrayName].splice(index, 1);
            }
        },
        async submitForm() {
            try {
                const response = await axios.post('/api/efficiency/add', this.formData);
                alert("Data saved successfully!");
                console.log(response.data);
            } catch (err) {
                console.error("Submission Error:", err.response ? err.response.data : err.message);
                alert("Error saving data. Check console.");
            }
        }
    }
}
</script>

<style scoped>
.grid-input {
    width: 100%;
    border: none;
    padding: 4px 8px;
    background: transparent;
    outline: none;
    text-align: center;
}

.grid-input:focus {
    background-color: #f8f9fa;
    box-shadow: inset 0 0 0 1px #0d6efd;
}

.table th {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.main-scroll-area::-webkit-scrollbar {
    width: 6px;
}

.main-scroll-area::-webkit-scrollbar-thumb {
    background-color: #ced4da;
    border-radius: 10px;
}
</style>
