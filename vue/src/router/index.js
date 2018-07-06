import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Overview from '../components/Overview'
import Detail from '../components/Detail'
import CreateVehicle from '../components/CreateVehicle'
import UpdateVehicle from '../components/UpdateVehicle'
import Profile from '../components/Profile'
import Dashboard from '../components/Dashboard'
import Login from '../components/Login'
import Register from '../components/Register'
import RentVehicle from '../components/RentVehicle'

Vue.use(Router);

let routes = [
  { path: '/', component: Home },
  { path: '/aanmelden', component: Login },
  { path: '/registreren', component: Register },
  { path: '/overzicht', component: Overview },
  { path: '/profiel/:id', component: Profile },
  { path: '/dashboard', component: Dashboard },
  { path: '/voertuig/nieuw', component: CreateVehicle },
  { path: '/voertuig/:id/bewerken', component: UpdateVehicle },
  { path: '/overzicht/voertuig/:id', component: Detail },
  { path: '/overzicht/voertuig/:id/huren', component: RentVehicle },
];

export default new Router({
    routes,
    mode: 'history',
    scrollBehavior (to, from, savedPosition) { return { x: 0, y: 0 } }
});
