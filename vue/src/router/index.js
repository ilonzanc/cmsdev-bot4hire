// Website
import Vue from 'vue'
import Router from 'vue-router'
import Home from '../pages/Home'
import Overview from '../pages/Overview'
import Detail from '../pages/Detail'
import Profile from '../pages/Profile'
import Login from '../pages/Login'
import Register from '../pages/Register'
import RentVehicle from '../pages/RentVehicle'

// Dashboard
import Dashboard from '../pages/dashboard/Dashboard'

import YourVehicles from '../pages/dashboard/YourVehicles'
import YourVehicle from '../pages/dashboard/YourVehicle'
import CreateVehicle from '../pages/dashboard/CreateVehicle'
import UpdateVehicle from '../pages/dashboard/UpdateVehicle'

import YourRentals from '../pages/dashboard/YourRentals'
import YourRental from '../pages/dashboard/YourRental'

import UpdateProfile from '../pages/dashboard/UpdateProfile'

Vue.use(Router);

let routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/overview', component: Overview },
  { path: '/overview/vehicle/:id', component: Detail },
  { path: '/overview/vehicle/:id/rent', component: RentVehicle },
  { path: '/profile/:id', component: Profile },
  { path: '/dashboard', component: Dashboard },
  { path: '/dashboard/vehicles', component: YourVehicles },
  { path: '/dashboard/vehicles/new', component: CreateVehicle },
  { path: '/dashboard/vehicles/:id', component: YourVehicle },
  { path: '/dashboard/vehicles/:id/edit', component: UpdateVehicle },
  { path: '/dashboard/rentalcontracts', component: YourRentals },
  { path: '/dashboard/rentalcontracts/:id', component: YourRental },
  { path: '/dashboard/profile/edit', component: UpdateProfile },
];

export default new Router({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});
