<template>
    <div class="report-wrapper shadow border">
        <!-- Header Summary Controls -->
        <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex gap-3 align-items-center">
                <div class="input-group input-group-sm w-auto">
                    <span class="input-group-text bg-light fw-bold">Month</span>
                    <input type="month" v-model="filterMonth" class="form-control" @change="fetchReportData">
                </div>
                <h5 class="mb-0 fw-bold text-dark">Master Efficiency Report (All Units)</h5>
            </div>
            <button class="btn btn-sm btn-primary" @click="fetchReportData">
                Refresh All Data
            </button>
        </div>

        <div class="report-container">
            <table class="report-table table table-hover table-bordered">
                <thead class="sticky-header">
                    <tr class="header-row-1">
                        <th rowspan="2" class="sticky-col">Line</th>
                        <th colspan="3" class="sticky-col">Monthly Manpower</th>
                        <th rowspan="2">Working Day</th>
                        <th rowspan="2">Target</th>
                        <th rowspan="2">Actual</th>
                        <th rowspan="2">Shortage</th>
                        <th colspan="2">Efficiency</th>
                        <th rowspan="2">Avg SMV</th>
                        <th colspan="3">Earnings</th>
                        <th colspan="4" class="cost-header">Line Cost</th>
                    </tr>
                    <tr class="header-row-2">
                        <th class="sticky-col manpower-sub">OP</th>
                        <th class="sticky-col manpower-sub">HP</th>
                        <th class="sticky-col manpower-sub">Total</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Total</th>
                        <th>Avg</th>
                        <th>Per HP</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Belt</th>
                        <th>Low Cost</th>
                    </tr>
                </thead>

                <!-- Outer Loop: Iterate through every Unit Group -->
                <tbody v-for="(lines, unitName) in unitGroups" :key="unitName">
                    <tr class="unit-divider">
                        <td colspan="18" class="text-start ps-3 fw-bold text-uppercase">
                            <i class="bi bi-building me-2"></i>Unit: {{ unitName }}
                        </td>
                    </tr>

                    <!-- Inner Loop: Show all Lines for this specific Unit -->
                    <tr v-for="line in lines" :key="line.id">
                        <td class="sticky-col first-col line-no">{{ line.line_no }}</td>
                        <td class="sticky-col manpower-cell">{{ line.op }}</td>
                        <td class="sticky-col manpower-cell">{{ line.hp }}</td>
                        <td class="sticky-col manpower-cell total-bg">{{ Number(line.op || 0) + Number(line.hp || 0) }}
                        </td>
                        <td>{{ line.working_days || 0 }}</td>
                        <td>{{ line.target || 0 }}</td>
                        <td>{{ line.actual || 0 }}</td>
                        <td :class="(line.actual - line.target) < 0 ? 'text-danger' : ''">
                            {{ (line.actual || 0) - (line.target || 0) }}
                        </td>
                        <td>{{ line.target_eff || 0 }}%</td>
                        <td class="fw-bold">{{ calcEff(line) }}%</td>
                        <td>{{ line.avg_smv || 0 }}</td>
                        <td>${{ line.total_earn || 0 }}</td>
                        <td>${{ line.avg_earn || 0 }}</td>
                        <td>${{ line.per_hp_earn || 0 }}</td>
                        <td>${{ line.target_cost || 0 }}</td>
                        <td>${{ line.actual_cost || 0 }}</td>
                        <td>${{ line.belt_cost || 0 }}</td>
                        <td class="fw-bold text-success">${{ line.low_cost || 0 }}</td>
                    </tr>

                    <!-- Unit-Wise Footer Totals -->
                    <tr class="unit-total-row">
                        <td colspan="5" class="text-end pe-3 fw-bold">Unit {{ unitName }} Total:</td>
                        <td>{{ getUnitTotal(lines, 'target') }}</td>
                        <td>{{ getUnitTotal(lines, 'actual') }}</td>
                        <td
                            :class="(getUnitTotal(lines, 'actual') - getUnitTotal(lines, 'target')) < 0 ? 'text-danger' : ''">
                            {{ getUnitTotal(lines, 'actual') - getUnitTotal(lines, 'target') }}
                        </td>
                        <td>-</td>
                        <td class="text-primary fw-bold">{{ getUnitAvgEff(lines) }}%</td>
                        <td colspan="8"></td>
                    </tr>
                </tbody>

                <!-- Grand Totals (All Units Combined) -->
                <tfoot class="sticky-footer bg-dark text-white">
                    <tr>
                        <td colspan="5" class="text-end pe-3 fw-bold">Grand Total:</td>
                        <td>{{ grandTotals.target }}</td>
                        <td>{{ grandTotals.actual }}</td>
                        <td>{{ grandTotals.actual - grandTotals.target }}</td>
                        <td>-</td>
                        <td>{{ grandTotals.avgEff }}%</td>
                        <td colspan="8"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios'; // Ensure axios is installed

export default {
    data() {
        return {
            filterMonth: new Date().toISOString().substr(0, 7),
            reportData: [], // Start with empty array
            loading: false
        };
    },
    computed: {
        // Automatically groups reportData whenever it is updated by the fetch
        unitGroups() {
            return this.reportData.reduce((groups, item) => {
                const unit = item.unit_name || 'General';
                if (!groups[unit]) groups[unit] = [];
                groups[unit].push(item);
                return groups;
            }, {});
        },
        grandTotals() {
            const target = this.reportData.reduce((s, l) => s + (Number(l.target) || 0), 0);
            const actual = this.reportData.reduce((s, l) => s + (Number(l.actual) || 0), 0);
            return {
                target,
                actual,
                avgEff: target > 0 ? ((actual / target) * 100).toFixed(1) : 0
            };
        }
    },
    methods: {
        async fetchReportData() {
            this.loading = true;
            try {
                // Adjust the URL to your actual backend endpoint
                const response = await axios.get('/api/production-report', {
                    params: { month: this.filterMonth }
                });
                this.reportData = response.data;
            } catch (error) {
                console.error("Error fetching report:", error);
                alert("Failed to load data.");
            } finally {
                this.loading = false;
            }
        },
        calcEff(line) {
            if (!line.target || line.target <= 0) return 0;
            return ((line.actual / line.target) * 100).toFixed(1);
        },
        getUnitTotal(lines, field) {
            return lines.reduce((sum, line) => sum + (Number(line[field]) || 0), 0);
        },
        getUnitAvgEff(lines) {
            const target = this.getUnitTotal(lines, 'target');
            const actual = this.getUnitTotal(lines, 'actual');
            return target > 0 ? ((actual / target) * 100).toFixed(1) : 0;
        }
    },
    mounted() {
        this.fetchReportData(); // Fetch data when page loads
    }
}

</script>
<style scoped>
/* 1. Ensure the container has a fixed height or overflow */
.report-container {
    max-height: 80vh;
    /* Adjust based on your layout */
    overflow: auto;
    position: relative;
}

/* 2. Sticky Header Logic */
.sticky-header {
    position: sticky;
    top: 0;
    z-index: 10;
    /* Higher than sticky columns */
    background-color: #fff;
}

/* For double-row headers, offset the second row */
.header-row-1 th {
    position: sticky;
    top: 0;
}

/* Adjust the '40px' below to match the actual height of your first header row */
.header-row-2 th {
    position: sticky;
    top: 40px;
    z-index: 10;
}

/* 3. Sticky Footer Logic */
.sticky-footer {
    position: sticky;
    bottom: 0;
    z-index: 10;
    background-color: #FF8C00 !important;
    /* Matches bg-dark */
    color: white;
}

/* 4. Fix for borders during scroll */
.sticky-header th,
.sticky-footer td {
    box-shadow: inset 0 1px 0 #dee2e6, inset 0 -1px 0 #dee2e6;
}

/* 5. Sticky Columns (Line No) */
.sticky-col {
    position: sticky;
    left: 0;
    background-color: #fff;
    z-index: 5;
}

/* Offset for multiple sticky columns if needed */
.first-col {
    left: 0;
    z-index: 6;
}

.second-col {
    left: 80px;
    z-index: 5;
}

.table>:not(caption)>*>* {
    padding: .1rem .1rem;
}
</style>
