<template>
    <div id="home">
    <div class="hero__image">
      <div class="container">
        <div class="hero__image-text">
          <!-- TODO: Final text and layouting -->
          <h1>Astrovia</h1>
          <p>Robots n stuff</p>
          <router-link class="btn" to="/overview">Check the catalogue</router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <h2><i class="fa fa-star-o" style="fontSize: 1rem; position: relative; bottom: 2px"></i> Newest vehicles</h2>
      <div class="row">
        <vehicle v-bind:key="vehicle.index" :vehicle="vehicle" v-for="vehicle in vehicles"></vehicle>
      </div>
    </div>
  </div>
</template>

<script>
import Vehicle from '../components/Vehicle.vue';

export default {
  name: 'home',
  components:{
    'vehicle': Vehicle,
  },
  data () {
    return {
      vehicles: [],
      isLoaded: false
    }
  },
  mounted() {
    axios({
      method: "get",
      url: apiurl + "api/v1.0/vehicles?_format=hal_json",
    })
    .then((response) => {
      console.log(response);
      this.isLoaded = true;
      let latestThree = response.data.slice((response.data.length - 3),response.data.length);
      latestThree.reverse();
      this.vehicles = latestThree;
    })
    .catch((error) => {
      if (error.response) {
        console.log(error.response.status);
        console.log(error.response.headers);
        console.log(error.response.data.message);

        let errors = [];

      } else if (error.request) {
        console.log(error.request);
      } else {
        console.log('Error', error.message);
      }
      console.log(error.config);
    });
  },
  methods: {

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
