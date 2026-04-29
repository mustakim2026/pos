<template>
    <div class="card shadow border-0">
        <form @submit.prevent="submitForm">
            <!-- HEADER INFO -->
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
                    <!-- Trigger Button (Your existing Pill) -->
                    <div class="col-md-4 text-md-end" data-bs-toggle="modal" data-bs-target="#observerModal"
                        style="cursor: pointer;">
                        <div class="d-inline-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                            <label class="small fw-bold mb-0 me-2">Observer:</label>
                            <div class="small text-muted">{{ formData.observer || 'Select Man...' }}</div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow border-0">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="fw-bold">Add New User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Full Name</label>
                                        <input type="text" class="form-control" placeholder="Enter name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Role</label>
                                        <select class="form-select">
                                            <option value="Observer">Observer</option>
                                            <option value="Admin">Admin</option>
                                            <option value="User">Standard User</option>
                                        </select>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold"
                                            data-bs-dismiss="modal">
                                            Save User
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

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

            <div class="main-scroll-area" style="max-height: 500px; overflow-y: auto;">
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
                            <th style="width: 50px;">Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in formData.outputRows" :key="'out-' + index">
                            <td class="bg-light fw-bold align-middle">{{ index + 1 }}</td>
                            <td><input v-model="row.line_no" class="grid-input"
                                    @focus="index === formData.outputRows.length - 1 && addRow()"></td>
                            <td><input v-model="row.po_no" class="grid-input"></td>
                            <td><input v-model="row.style_model_no" class="grid-input"></td>
                            <td><input v-model="row.item" class="grid-input"></td>
                            <td><input v-model.number="row.target" type="number" class="grid-input"></td>
                            <td><input v-model.number="row.output" type="number" class="grid-input"></td>
                            <td>
                                <button type="button" class="btn btn-sm text-danger"
                                    @click="removeRow(index)">&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- TAB 2: ATTENDANCE (Auto-synced) -->
                <table v-if="activeTab === 2" class="table table-bordered text-center mb-0">
                    <thead class="sticky-top bg-light">
                        <tr class="small fw-bold">
                            <th rowspan="2" style="width: 50px;">SL</th>
                            <th rowspan="2">Line</th>
                            <th rowspan="2">Po No</th>
                            <th rowspan="2">Style</th>
                            <th rowspan="2">Item</th>
                            <th rowspan="2">Allocated Hrs</th>
                            <th colspan="2">8:00-1:00 PM</th>
                            <th colspan="2">2:00-5:00 PM</th>
                            <th colspan="2">5:00-6:00 PM</th>
                            <th colspan="2">6:00-7:00 PM</th>
                            <th colspan="2">7:00-8:00 PM</th>
                            <th colspan="2">8:00-9:00 PM</th>
                            <th colspan="2">9:00-10:00 PM</th>
                            <th colspan="1">10> after Ten</th>
                            <th colspan="3" rowspan="2" class="align-middle">Total</th>
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
                            <th>Working/Hrs</th>
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
                            <td><input v-model.number="row.w_r" type="number" class="grid-input"></td>
                            <td colspan="3" class="align-middle fw-bold">
                                {{ calculateTotal(row) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-light border-top text-end">
                <button type="submit" class="btn btn-primary px-4">Submit Report</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            activeTab: 1,
            formData: {
                report_date: new Date().toISOString().substr(0, 10),
                unit_name: '',
                observer: '',
                outputRows: [this.createNewRow()]
            }
        }
    },
    methods: {
        createNewRow() {
            return {
                line_no: '', po_no: '', style_model_no: '', item: '', target: 0, output: 0,
                allocated_Hrs: 0, w_r: 0,
                OP1: 0, HP1: 0, OP2: 0, HP2: 0, OP3: 0, HP3: 0, OP4: 0, HP4: 0,
                OP5: 0, HP5: 0, OP6: 0, HP6: 0, OP7: 0, HP7: 0
            };
        },
        addRow() {
            this.formData.outputRows.push(this.createNewRow());
        },
        removeRow(index) {
            if (this.formData.outputRows.length > 1) {
                this.formData.outputRows.splice(index, 1);
            }
        },
        calculateTotal(row) {
            // Example total logic: Sum of all OP fields
            return (row.OP1 || 0) + (row.OP2 || 0) + (row.OP3 || 0) + (row.OP4 || 0) + (row.OP5 || 0) + (row.OP6 || 0) + (row.OP7 || 0);
        },
        submitForm() {
            console.log("Form Data Submitted:", this.formData);
            alert("Form submitted successfully!");
        }
    }
}
</script>

<style scoped>
.grid-input {
    width: 100%;
    border: none;
    outline: none;
    text-align: center;
    padding: 4px;
    background: transparent;
}

.grid-input:focus {
    background: #f8f9fa;
}

.table-bordered th,
.table-bordered td {
    padding: 0;
    vertical-align: middle;
}

.nav-link {
    cursor: pointer;
}
</style>
