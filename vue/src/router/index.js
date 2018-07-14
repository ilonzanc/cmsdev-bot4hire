import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Overview from '../components/Overview'
import Detail from '../components/Detail'
import Profile from '../components/Profile'
import Login from '../components/Login'
import Register from '../components/Register'
import RentVehicle from '../components/RentVehicle'

import Dashboard from '../components/dashboard/Dashboard'
import YourVehicles from '../components/dashboard/YourVehicles'
import YourVehicle from '../components/dashboard/YourVehicle'
import CreateVehicle from '../components/dashboard/CreateVehicle'
import UpdateVehicle from '../components/dashboard/UpdateVehicle'

Vue.use(Router);

let routes = [
  { path: '/', component: Home },
  { path: '/aanmelden', component: Login },
  { path: '/registreren', component: Register },
  { path: '/overzicht', component: Overview },
  { path: '/profiel/:id', component: Profile },
  { path: '/overzicht/voertuig/:id', component: Detail },
  { path: '/overzicht/voertuig/:id/huren', component: RentVehicle },
  { path: '/dashboard', component: Dashboard },
  { path: '/dashboard/vehicles', component: YourVehicles },
  { path: '/dashboard/vehicles/:id', component: YourVehicle },
  { path: '/dashboard/vehicles/new', component: CreateVehicle },
  { path: '/dashboard/vehicles/:id/edit', component: UpdateVehicle },
];

export default new Router({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});
