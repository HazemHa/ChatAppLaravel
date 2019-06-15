import Vue from 'vue'
import Router from 'vue-router';
import privateChat from '../components/chat/private.vue'
import publicChat from '../components/chat/messages.vue'
import presenceChat from '../components/chat/presence2.vue'
import Home from '../components/Home.vue'
import login from '../components/user/login.vue'
import register from '../components/user/register.vue'
import profile from '../components/user/profile.vue'
import AuthGuard from './auth.js'
Vue.use(Router);


const routerApp = new Router({
    mode: 'history',

    // beforeEnter => check the user is Auth or Not!!
    routes: [{
            name: 'home',
            path: '/',
            component: Home
        },
        {
            path: '/login',
            name: 'login',
            component: login
        }, {
            path: '/register',
            name: 'register',
            component: register
        },

        {
            path: '/profile',
            name: 'profile',
            component: profile,
        }, // when /profile/:id/likes is matched
        {
            name: 'private',
            path: '/p/:id',
            component: privateChat,
            beforeEnter: AuthGuard
        },
        {
            name: 'room',
            path: '/room/:id',
            component: presenceChat,
            beforeEnter: AuthGuard,
        }
    ]
});

export default routerApp;
