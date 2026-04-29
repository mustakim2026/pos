<template>
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mx-auto" style="max-width: 600px; float: left;">

        <!-- HEADER WITH TABS -->
        <div class="card-header bg-white p-0 border-bottom">
            <div class="d-flex align-items-center px-3 pt-3">
                <h6 class="mb-0 fw-bold text-dark me-auto">Observer Management</h6>
                <span class="badge bg-light text-primary border rounded-pill px-3">
                    {{ observers.length }} Total
                </span>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs border-bottom-0 mt-2 px-3">
                <li class="nav-item">
                    <button class="nav-link px-4" :class="{ 'active fw-bold': activeTab === 'form' }"
                        @click="activeTab = 'form'">
                        Entry Form
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4" :class="{ 'active fw-bold': activeTab === 'list' }"
                        @click="activeTab = 'list'">
                        View List
                    </button>
                </li>
            </ul>
        </div>

        <!-- CONTENT AREA -->
        <div class="card-body p-0">

            <div v-if="activeTab === 'form'">
                <form @submit.prevent="submitForm">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th class="text-center" style="width: 50px;">SL</th>
                                <th>Observer Name</th>
                                <th>Remarks</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in formData.outputRows" :key="'in-' + index">
                                <td class="text-center align-middle bg-light-subtle fw-bold small">{{ index + 1 }}</td>
                                <td class="p-0">
                                    <input v-model="row.observer_name" class="grid-input" placeholder="Type name..."
                                        @focus="index === formData.outputRows.length - 1 && addOutputRow()">
                                </td>
                                <td class="p-0">
                                    <input v-model="row.remarks" class="grid-input text-muted" placeholder="...">
                                </td>
                                <td class="p-0 text-center align-middle">
                                    <button @click="removeRow(index)"
                                        class="btn text-danger border-0 p-0">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="p-3 bg-light border-top text-end">
                        <button class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm">Save
                        </button>
                    </div>
                </form>
            </div>

            <!-- List View -->
            <div v-if="activeTab === 'list'" class="p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th class="text-center" style="width: 50px;">SL</th>
                                <th>Observer Name</th>
                                <th>Remarks</th>
                                <th class="text-center" style="width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <tr v-for="(observer, index) in observers" :key="observer.id" style="text-align: center;">
                                <td class="text-center text-muted small fw-bold">{{ index + 1 }}</td>
                                <td class="fw-medium text-dark">{{ observer.observer_name }}</td>
                                <td class="text-muted small">{{ observer.remarks }}</td>
                                <td class="text-center">
                                    <button @click="editObserver(observer)"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 shadow-sm">
                                        Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="observers.length === 0">
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No data available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

    </div>
</template>


<script>
import axios from 'axios';
export default {
    data() {
        return {
            activeTab: 'form',
            formData: { outputRows: [{ observer_name: '', remarks: '' }] },
            savedObservers: [],
            observers: []
        };
    },
    mounted() {
        this.Observers();
    },
    methods: {
        addOutputRow() {
            this.formData.outputRows.push({ observer_name: '', remarks: '' });
        },
        removeRow(index) {
            if (this.formData.outputRows.length > 1) this.formData.outputRows.splice(index, 1);
        },
        Observers() {
            axios.get('/api/observer') // Make sure this is Route::get in Laravel
                .then(response => {
                    this.observers = response.data;
                })
                .catch(error => console.error("Error fetching data:", error));
        },
        resetForm() {
            this.formData.outputRows = [{ observer_name: '', remarks: '' }];
        },
        async submitForm() {
            try {
                // Removed the trailing space from the URL
                const res = await axios.post('/api/observer/add', this.formData);
                alert("Data Saved Successfully!");
                this.resetForm();
            } catch (err) {
                if (err.response && err.response.status === 422) {
                    // This will show you exactly which field failed validation
                    const errors = err.response.data.errors;
                    console.error("Validation Errors:", errors);
                    alert("Validation Failed: " + JSON.stringify(errors));
                } else {
                    console.error("Save Error:", err);
                    alert("Save Failed!");
                }
            }
        }
    }
};
</script>

<<style scoped>
/* Tab Styling */
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    padding-bottom: 12px;
}

.nav-tabs .nav-link.active {
    background: transparent;
    color: #0d6efd;
    border-bottom-color: #0d6efd;
}

/* Input Styling */
.grid-input {
    width: 100%;
    border: none;
    padding: 12px;
    font-size: 14px;
    outline: none;
    background: transparent;
}

.grid-input:focus {
    background: #f8fbff;
}

/* Card Styling */
.card {
    border: 1px solid #eee;
}

.table>:not(caption)>*>* {
    padding: .1rem .1rem;
}


.px-3 {
    padding-right: 1rem !important;
    padding-left: 1rem !important;}



    
</style>