import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Overview from '@/components/Overview'
import CreateVehicle from '@/components/CreateVehicle'
import Detail from '@/components/Detail'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/overview',
      name: 'Overview',
      component: Overview
    },
    {
      path: '/voertuig/nieuw',
      name: 'CreateVehicle',
      component: CreateVehicle
    },
    {
      path: '/voertuig/:id',
      name: 'Detail',
      component: Detail
    },
  ]
})
