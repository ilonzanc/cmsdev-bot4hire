<template>
    <div id="home">
    <div class="hero__image">
      <div class="container">
        <div class="hero__image-text">
          <h1>Bot4Hire</h1>
          <p>Wissel je wagen uit met anderen. Super handig en goed voor het milieu!</p>
          <router-link class="btn secundary-btn" to="/overzicht">Bekijk de catalogus</router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <h2>Nieuwste voertuigen</h2>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="vehicle.index" v-for="vehicle in vehicles">
          <section class="section__vehicle">
            <router-link :to='"overzicht/voertuig/" + vehicle.id'>
              <div class="row">
                <div class="column column-sm-3 column-12">
                  <div class="vehicle__image" :style='"background: url(" + vehicle.image + ") no-repeat center; background-size: contain"'></div>
                </div>
                <div class="column column-sm-9 column-12">
                  <i class="fa fa-angle-right"></i>
                  <h2>{{vehicle.name}}</h2>
                  <p>{{vehicle.vehicle_type}}</p>
                </div>
              </div>
            </router-link>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'home',
  data () {
    return {
      user: this.$parent.user,
      password: this.$parent.user_password,
      vehicles: []
    }
  },
  mounted() {
    axios({
      method: "get",
      url: apiurl + "api/v1.0/vehicles?_format=hal_json",
    })
    .then((response) => {
      console.log(response);
      let latestThree = response.data.slice((response.data.length - 3),response.data.length);
      latestThree.reverse();
      this.vehicles = latestThree;
    })
    .catch((error) => {
      console.log(error);
    });
  },
  methods: {

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
