<template>
  <div class="column column-sm-12 column-4">
      <section v-if="!loaded">
        <loader></loader>
      </section>
      <section :class="'section__vehicle ' + { hidden: !loaded, loaded: loaded}">
        <router-link :to='"overview/vehicle/" + vehicle.id'>
          <div class="image-border">
            <div class="vehicle__image" :style='"background-image: url( http://localhost:8888" + vehicle.image_url + ")"'></div>
          </div>
          <i class="fa fa-angle-right"></i>
          <h2>{{vehicle.name}}</h2>
          <p>{{vehicle.vehicle_type_name}}</p>
          <section class="vehicle-price">
            <span class="vehicle-price-number">{{vehicle.price}}</span>
            <span class="vehicle-price-suffix">shanix</span>
          </section>
        </router-link>
      </section>
    </div>
</template>

<script>
  import Loader from './Loader.vue';

  export default {
    name: 'vehicle',
    components:{
      'loader': Loader,
    },
    props: ['vehicle'],
    data() {
      return {
        loaded: false
      }
    },
    mounted () {
      console.log("Not loaded")
      if (this.vehicle) {
        this.loaded = true;
      }
    },
    methods: {

    }
  }
</script>