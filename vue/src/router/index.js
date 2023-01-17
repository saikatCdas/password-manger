import {createRouter, createWebHistory} from 'vue-router';
import AuthLayout from '../components/authLayout/AuthLayout.vue'
import Register from '../components/authLayout/Registration.vue';
import Login from '../components/authLayout/Login.vue';
import NotFound from '../components/NotFound.vue';
import DefaultLayout from '../components/DefaultLayout.vue';
import Vaults from '../views/Vaults.vue';
import Tools from '../views/Tools.vue';
import PasswordGenerator from '../components/Tools/PasswordGenerator.vue';
import Imports from '../components/Tools/Imports.vue';
import Exports from '../components/Tools/Exports.vue';
import store from '../store';


const routes = [
    {
        path: "/",
        redirect: "/vaults",
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            {path: '/vaults', name: 'Vaults', component: Vaults},
            {path: '/tools', name: 'Tools', component: Tools,
            children:[
                {path: '/tools/password-generator', name:'PasswordGenerator', component:PasswordGenerator},
                {path: '/tools/imports', name:'Imports', component:Imports},
                {path: '/tools/exports', name:'Exports', component:Exports},
            ]
            }
        ],
      },
    {
        path: "/auth",
        redirect: "/login",
        name: "Auth",
        component: AuthLayout,
        meta: {isGuest: true},
        children: [
            {
            path: "/login",
            name: "Login",
            component: Login,
            },
            {
            path: "/register",
            name: "Register",
            component: Register,
            },
        ],
    },
    { path: '/:pathMatch(.*)*', name: 'NotFound',component: NotFound }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
  });


router.beforeEach((to, from, next) => {
if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "Login" });
} else if (store.state.user.token && to.meta.isGuest) {
    next({ name: "Dashboard" });
} else {
    next();
}
});

export default router;
