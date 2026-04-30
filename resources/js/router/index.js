import { createRouter, createWebHistory } from "vue-router";


const routes = [
    {
        path: "/",
        name: "UserList",
        component: () => import("../components/Crud/UserList.vue"),
    },
    {
        path: "/Add",
        name: "add",
        component: () => import("../components/Crud/add.vue"),
    },
    {
        path: "/Profile",
        name: "Profile",
        component: () => import("../components/companyProfile/Profile.vue"),
    },
    {
        path: "/DailyEntryForm",
        name: "DailyEntryForm",
        component: () => import("../components/EfficiencyReport/DailyEntryForm.vue"),
    },
    {
        path: "/LeftSidebar",
        name: "LeftSidebar",
        component: () => import("../components/companyProfile/LeftSidebar.vue"),
    },
    {
        path: "/observe",
        name: "observe",
        component: () => import("../components/setting/observer_name.vue"),
    },
    {
        path: "/unit",
        name: "unit",
        component: () => import("../components/setting/unit.vue"),
    },
    {
        path: "/report",
        name: "report",
        component: () => import("../components/EfficiencyReport/Report.vue"),
    },
    {
        path: "/others",
        name: "others",
        component: () => import("../components/companyProfile/others.vue"),
    },
    {
        path: "/smv_library",
        name: "smv_library",
        component: () => import("../components/setting/smv_library..vue"),
    },
]



const router = createRouter({
    history: createWebHistory(),
    routes // This passes the array above into the configuration
})


export default router;
