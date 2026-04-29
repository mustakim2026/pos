<template>
    <div class="card shadow border-0">
        <form @submit.prevent="submitForm">
            <!-- HEADER INFO -->
            <div class="p-3 border-bottom bg-white">
                <div class="row align-items-center g-3">
                    <!-- Date & Unit Section -->
                    <div class="col-md-8 d-flex gap-3">
                        <div class="input-group input-group-sm w-auto shadow-sm">
                            <span class="input-group-text bg-light fw-bold border-end-0">Date</span>
                            <input v-model="formData.report_date" type="date" class="form-control">
                        </div>

                        <!-- UNIT SELECT DROPDOWN -->
                        <div class="input-group input-group-sm w-auto shadow-sm">
                            <span class="input-group-text bg-light fw-bold border-end-0">Unit</span>
                            <select v-model="formData.unit_id" class="form-select" @change="handleUnitChange">
                                <option value="" disabled>Select Unit...</option>
                                <option v-for="unit in units" :key="unit.id" :value="unit.id">
                                    {{ unit.unit_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group input-group-sm w-auto shadow-sm rounded-pill overflow-hidden border">
                        <!-- Left Side (Label) -->
                        <span class="input-group-text bg-light fw-bold border-0 px-3">Observer</span>

                        <!-- Right Side (Select) -->
                        <select v-model="formData.observer" class="form-select border-0 bg-white"
                            style="min-width: 140px;">
                            <option value="" disabled>Select Observer...</option>
                            <option v-for="item in observerList" :key="item.id" :value="item.observer_name">
                                {{ item.observer_name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Add your Bottom Popup Form component here (from previous step) -->


            <!-- TABS -->
            <ul class="nav nav-tabs px-3 bg-white border-bottom">
                <li class="nav-item">
                    <button type="button" class="nav-link" :class="{ active: activeTab === 1 }"
                        @click="activeTab = 1">Daily Output</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" :class="{ active: activeTab === 2 }"
                        @click="activeTab = 2">Daily Attendance</button>
                </li>
            </ul>

            <div ref="scrollArea" class="main-scroll-area" style="max-height: 500px; overflow-y: auto;">
                <!-- TAB 1: OUTPUT (Editable) -->
                <table v-if="activeTab === 1" class="table table-bordered text-center mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th style="width: 50px;">SL</th>
                            <th>Line</th>
                            <th>PO No</th>
                            <th>Style</th>
                            <th>Item</th>
                            <th>Target</th>
                            <th>Output</th>
                            <th>Del</th>
                        </tr>

                    </thead>
                    <tfoot class="bg-light fw-bold">

                        <tr>
                            <td colspan="5" class="text-end px-3">Grand Total:</td>
                            <td>{{ totalTarget }}</td>
                            <td>{{ totalOutput }}</td>
                            <td></td>
                        </tr>


                    </tfoot>
                    <tbody>
                        <tr v-for="(row, index) in formData.outputRows" :key="'out-' + index">
                            <td class="bg-light fw-bold align-middle">{{ index + 1 }}</td>
                            <td>
                                <select v-model="row.line_no" class="grid-input" :disabled="!formData.unit_id"
                                    @change="index === formData.outputRows.length - 1 && addOutputRow()">
                                    <option value="" disabled>
                                        {{ formData.unit_id ? 'Select line' : 'Select line' }}
                                    </option>
                                    <!-- Use line.line_no as the value so the name appears in your table -->
                                    <option v-for="line in filteredLines" :key="line.id" :value="line.line_no">
                                        {{ line.line_no }}
                                    </option>
                                </select>
                            </td>
                            <td><input v-model="row.po_no" class="grid-input"></td>
                            <td><input v-model="row.style_model_no" class="grid-input"></td>
                            <td><input v-model="row.item" class="grid-input"></td>
                            <td><input v-model.number="row.target" type="number" class="grid-input"></td>
                            <td><input v-model.number="row.output" type="number" class="grid-input"></td>
                            <td><button type="button" class="btn btn-sm text-danger"
                                    @click="removeRow('outputRows', index)">&times;</button></td>
                        </tr>
                    </tbody>
                </table>

                <!-- TAB 2: ATTENDANCE (Auto-synced) -->
                <div class="table-container shadow-sm border" style="max-height: 500px; overflow: auto;">
                    <table v-if="activeTab === 2" class="table table-bordered text-center mb-0"
                        style=" table-layout: fixed;">
                        <thead class="sticky-top bg-light">
                            <tr class="small fw-bold">
                                <th rowspan="2" style="width: 50px;">SL</th>
                                <th rowspan="2">Line</th>
                                <th rowspan="2">Po No</th>
                                <th rowspan="2">Style/ model</th>
                                <th rowspan="2">Item</th>
                                <th rowspan="2">Allocated Hrs</th>
                                <th colspan="2">8:00-1:00 PM</th>
                                <th colspan="2">2:00-5:00 PM</th>
                                <th colspan="2">5:00-6:00 PM</th>
                                <th colspan="2">6:00-7:00 PM</th>
                                <th colspan="2">7:00-8:00 PM</th>
                                <th colspan="2">8:00-9:00 PM</th>
                                <th colspan="2">9:00-10:00 PM</th>
                                <th colspan="3">10> after Ten</th>
                                <th colspan="2" class="align-middle">Total</th>
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
                                <th>W/Hrs</th>
                                <th>OP</th>
                                <th>HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in formData.outputRows" :key="'att-' + index">
                                <td class="bg-light fw-bold align-middle">{{ index + 1 }}</td>
                                <td><input v-model="row.line_no" class="grid-input bg-light" readonly></td>
                                <td><input v-model="row.po_no" class="grid-input bg-light" readonly></td>
                                <td><input v-model="row.style_model_no" class="grid-input bg-light" readonly></td>
                                <td><input v-model="row.item" class="grid-input bg-light" readonly></td>

                                <td><input v-model.number="row.allocated_Hrs" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP1" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP1" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP2" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP2" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP3" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP3" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP4" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP4" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP5" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP5" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP6" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP6" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP7" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP7" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.OP8" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.HP8" type="number" class="grid-input"></td>
                                <td><input v-model.number="row.w_hrs" type="number" class="grid-input"></td>
                                <td>
                                    {{ TotalOP(row) }}
                                </td>
                                <td>
                                    {{ TotalHP(row) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary px-5" style="margin: 10px;">Save Report</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            units: [],
            lines: [],
            observerList: [],
            activeTab: 1,
            formData: {
                report_date: new Date().toISOString().substr(0, 10),
                unit_id: '',
                observer: '',
                outputRows: [{ line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0, remarks: '' }],
                attendanceRows: []
            }
        }
    },
    watch: {
        // COMBINED WATCHERS INTO ONE OBJECT
        'formData.outputRows': {
            handler(newRows) {
                newRows.forEach((row, index) => {
                    if (!this.formData.attendanceRows[index]) {
                        this.formData.attendanceRows.push(this.initAttendanceRow());
                    }
                    const target = this.formData.attendanceRows[index];
                    target.line_no = row.line_no;
                    target.po_no = row.po_no;
                    target.style_model_no = row.style_model_no;
                    target.item = row.item;
                });
                if (this.formData.attendanceRows.length > newRows.length) {
                    this.formData.attendanceRows.splice(newRows.length);
                }
            },
            deep: true,
            immediate: true
        },
        'formData.unit_name': function (newUnit) {
            if (newUnit && newUnit !== 'undefined') {
                this.fetchLines(newUnit);
            } else {
                this.lines = [];
            }
            // Reset line selection when unit changes
            this.formData.outputRows.forEach(row => row.line_no = '');
        }
    },
    mounted() {
        this.fetchObservers();
        this.fetchUnits();
        this.fetchLines();
    },
    computed: {
        filteredLines() {
            // Use 'unit_id' (not unit_name) to match your database JSON
            if (!this.formData.unit_id) return [];

            return this.lines.filter(line => {
                // Convert both to numbers to be 100% sure they match
                return Number(line.unit_id) === Number(this.formData.unit_id);
            });
        }
    },// <-- Bracket correctly closed here
    methods: {
        initAttendanceRow() {
            return {
                efficiency_id: '', line_no: '', po_no: '', style_model_no: '', item: '', allocated_Hrs: '',
                OP1: 0, HP1: 0, OP2: 0, HP2: 0, OP3: 0, HP3: 0, OP4: 0, HP4: 0, OP5: 0, HP5: 0, OP6: 0, HP6: 0, OP7: 0, HP7: 0, OP8: 0, HP8: 0, w_hrs: 0
            };
        },
        addOutputRow() {
            this.formData.outputRows.push({ line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0, remarks: '' });
        },
        removeRow(type, index) {
            if (this.formData[type].length > 1) this.formData[type].splice(index, 1);
        },
        TotalOP(row) {
            return (row.OP1 || 0) + (row.OP2 || 0) + (row.OP3 || 0) + (row.OP4 || 0) + (row.OP5 || 0) + (row.OP6 || 0) + (row.OP7 || 0);
        },
        TotalHP(row) {
            return (row.HP1 || 0) + (row.HP2 || 0) + (row.HP3 || 0) + (row.HP4 || 0) + (row.HP5 || 0) + (row.HP6 || 0) + (row.HP7 || 0);
        },
        resetForm() {
            this.formData = {
                report_date: new Date().toISOString().substr(0, 10),
                unit_name: '',
                observer: '',
                outputRows: [{ line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0, remarks: '' }],
                attendanceRows: []
            };
            this.lines = [];
        },
        async fetchObservers() {
            try {
                const response = await axios.get('/api/observer');
                this.observerList = response.data;
            } catch (error) {
                console.error("Failed to fetch observers:", error);
            }
        },
        async fetchUnits() {
            try {
                const response = await axios.get('/api/unit');
                this.units = response.data;
            } catch (error) {
                console.error("fetchUnits error:", error);
            }
        },
        async fetchLines() {
            try {
                const response = await axios.get('/api/lines');
                this.lines = response.data;
            } catch (error) {
                console.error("fetchLines error:", error);
            }
        },
        async submitForm() {
            try {
                // 1. Find the unit object that matches the selected ID
                const selectedUnit = this.units.find(u => u.id == this.formData.unit_id);

                // 2. Add the unit_name to the data so the backend validation passes
                if (selectedUnit) {
                    this.formData.unit_name = selectedUnit.unit_name;
                }

                const res = await axios.post('/api/efficiency/add', this.formData);
                alert("Data Saved Successfully!");
                this.resetForm();
            } catch (err) {
                if (err.response && err.response.status === 422) {
                    console.error("Validation Errors:", err.response.data.errors);
                    alert("Validation Failed! Check the unit selection.");
                }
            }
        }
    }
}
</script>


<style scoped>
.grid-input {
    width: 100%;
    border: none;
    text-align: center;
    padding: 2px;
    outline: none;
    background: transparent;
}

.grid-input:focus {
    background: #fffde7;
    border: 1px solid #ddd;
}

.table>:not(caption)>*>* {
    padding: .1rem .1rem;
}

.main-scroll-area {
    scroll-behavior: smooth;
    /* Makes the auto-scroll feel fluid */
}

.px-5 {
    padding-right: 1rem !important;
    padding-left: 1rem !important;
    border-radius: 10px;
    margin-left: 5px;
}

.rounded-pill {
    margin: 0 0 0 211px;
}

/* .table-container {
    position: relative;
    width: 100%;
    background: white;
} */

/* .sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
} */

/* th[rowspan="2"]:nth-child(1),
th[rowspan="2"]:nth-child(2) {
    z-index: 12;

} */

/* .grid-input {
    width: 50%;
    border: none;
    text-align: center;
    padding: 0px;
} */

/* .grid-input:focus {
    outline: 2px solid #007bff;
    background: #fff;
} */
</style>
