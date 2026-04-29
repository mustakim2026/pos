<template>
    <div class="report-wrapper p-3">
        <!-- Filter Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2 align-items-center">
                <label class="fw-bold">Month:</label>
                <input type="month" v-model="filterMonth" class="form-control form-control-sm w-auto"
                    @change="fetchReportData">
            </div>
            <h5 class="mb-0 fw-bold">MONTHLY EFFICIENCY & HP% REPORT</h5>
            <button class="btn btn-sm btn-primary" @click="fetchReportData">Refresh</button>
        </div>

        <div class="report-container">
            <table class="table table-bordered text-center mb-0">
                <thead class="sticky-header">
                    <tr class="header-main">
                        <th rowspan="2">Line</th>
                        <th colspan="4">Attendance (Manpower)</th>
                        <th rowspan="2">Days</th>
                        <th rowspan="2">Target</th>
                        <th rowspan="2">Actual</th>
                        <th rowspan="2">Variance</th>
                        <th rowspan="2">Eff %</th>
                    </tr>
                    <tr class="header-sub">
                        <th>OP</th>
                        <th>HP</th>
                        <th>HP %</th>
                        <th>Total</th>

                    </tr>
                </thead>

                <tbody v-for="(lines, unitName) in unitGroups" :key="unitName">
                    <!-- Unit Divider -->
                    <tr class="unit-row">
                        <td colspan="10" class="text-start ps-3 fw-bold">UNIT: {{ unitName }}</td>
                    </tr>

                    <!-- Line Data -->
                    <tr v-for="line in lines" :key="line.line_no">
                        <td class="fw-bold bg-light">{{ line.line_no }}</td>
                        <td>{{ line.op || 0 }}</td>
                        <td>{{ line.hp || 0 }}</td>
                        <td class="fw-bold text-primary">{{ calcHpPerc(line.op, line.hp) }}%</td>
                        <td class="fw-bold">{{ Number(line.op || 0) + Number(line.hp || 0) }}</td>
                        <!-- HP Percentage Line-wise -->


                        <td>{{ line.working_days }}</td>
                        <td>{{ line.target }}</td>
                        <td>{{ line.actual }}</td>
                        <td :class="line.actual < line.target ? 'text-danger' : 'text-success'">
                            {{ line.actual - line.target }}
                        </td>
                        <td class="fw-bold">{{ calcEff(line.actual, line.target) }}%</td>
                    </tr>

                    <!-- Unit Sub-Total -->
                    <tr class="unit-total-row">
                        <td class="text-end fw-bold">Unit Total:</td>
                        <td class="fw-bold">{{ sumField(lines, 'op') }}</td>
                        <td class="fw-bold">{{ sumField(lines, 'hp') }}</td>
                        <td class="fw-bold">{{ calcHpPerc(sumField(lines, 'op'), sumField(lines, 'hp')) }}%</td>
                        <td class="fw-bold">{{ sumField(lines, 'op') + sumField(lines, 'hp') }}</td>

                        <td>-</td>
                        <td class="fw-bold">{{ sumField(lines, 'target') }}</td>
                        <td class="fw-bold">{{ sumField(lines, 'actual') }}</td>
                        <td class="fw-bold">{{ sumField(lines, 'actual') - sumField(lines, 'target') }}</td>
                        <td class="fw-bold text-primary">{{ calcEff(sumField(lines, 'actual'), sumField(lines,
                            'target')) }}%</td>
                    </tr>
                </tbody>

                <!-- Grand Total Footer -->
                <tfoot class="sticky-footer">
                    <tr>
                        <td class="text-end">Grand Total:</td>
                        <td>{{ grandTotals.op }}</td>
                        <td>{{ grandTotals.hp }}</td>
                        <td>{{ calcHpPerc(grandTotals.op, grandTotals.hp) }}%</td>
                        <td>{{ grandTotals.op + grandTotals.hp }}</td>

                        <td>-</td>
                        <td>{{ grandTotals.target }}</td>
                        <td>{{ grandTotals.actual }}</td>
                        <td>{{ grandTotals.actual - grandTotals.target }}</td>
                        <td class="text-warning">{{ grandTotals.avgEff }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            filterMonth: new Date().toISOString().substr(0, 7),
            reportData: [],
            loading: false
        };
    },
    computed: {
        unitGroups() {
            return this.reportData.reduce((groups, item) => {
                const unit = item.unit_name || 'Others';
                if (!groups[unit]) groups[unit] = [];
                groups[unit].push(item);
                return groups;
            }, {});
        },
        grandTotals() {
            const target = this.reportData.reduce((s, l) => s + Number(l.target || 0), 0);
            const actual = this.reportData.reduce((s, l) => s + Number(l.actual || 0), 0);
            const op = this.reportData.reduce((s, l) => s + Number(l.op || 0), 0);
            const hp = this.reportData.reduce((s, l) => s + Number(l.hp || 0), 0);
            return { target, actual, op, hp, avgEff: target > 0 ? ((actual / target) * 100).toFixed(1) : 0 };
        }
    },
    methods: {
        async fetchReportData() {
            try {
                const res = await axios.get('/api/report/monthly', { params: { month: this.filterMonth } });
                this.reportData = Array.isArray(res.data) ? res.data : [];
            } catch (e) { console.error("Error:", e.response?.data?.error); }
        },
        calcEff(act, tar) { return tar > 0 ? ((act / tar) * 100).toFixed(1) : 0; },
        calcHpPerc(op, hp) {
            const total = Number(op || 0) + Number(hp || 0);
            return total > 0 ? ((Number(hp || 0) / total) * 100).toFixed(1) : 0;
        },
        sumField(lines, field) { return lines.reduce((s, l) => s + Number(l[field] || 0), 0); }
    },
    mounted() { this.fetchReportData(); }
}
</script>

<style scoped>
.report-container {
    max-height: 75vh;
    overflow: auto;
    border: 1px solid #dee2e6;
}

.table {
    border-collapse: separate;
    border-spacing: 0;
}

.sticky-header th {
    position: sticky;
    top: 0;
    z-index: 20;
    background-color: #212529 !important;
    color: white;
}

.sticky-header tr.header-sub th {
    top: 1px;
    z-index: 10;
}

.sticky-footer td {
    position: sticky;
    bottom: 0;
    z-index: 20;
    background-color: #212529 !important;
    color: #ffc107 !important;
    font-weight: bold;
}

.unit-row td {
    background-color: #e3f2fd !important;
    position: sticky;
    left: 0;
    z-index: 5;
}

.unit-total-row td {
    background-color: #f8f9fa !important;
    border-top: 2px solid #dee2e6;
}

.table>:not(caption)>*>* {
    padding: .1rem .1rem;

}
</style>
