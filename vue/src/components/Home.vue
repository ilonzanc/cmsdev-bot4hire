<template>
    <div id="home">
    <div class="hero__image">
      <div class="container">
        <div class="hero__image-text">
          <!-- TODO: Final text and layouting -->
          <h1>Astrovia</h1>
          <p>Robots n stff</p>
          <router-link class="btn secundary-btn" to="/overview">Check the catalogue</router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <h2><i class="fa fa-star-o" style="fontSize: 1rem; position: relative; bottom: 2px"></i> Newest vehicles</h2>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="vehicle.index" v-for="vehicle in vehicles">
          <section class="section__vehicle">
            <router-link :to='"overview/vehicle/" + vehicle.id'>
              <div class="row">
                <div class="column column-sm-3 column-12">
                  <div class="image-border">
                    <div class="vehicle__image" :style='"background-image: url( http://localhost:8888" + vehicle.image_url + ")"'></div>
                  </div>
                </div>
                <div class="column column-sm-9 column-12">
                  <i class="fa fa-angle-right"></i>
                  <h2>{{vehicle.name}}</h2>
                  <p>{{vehicle.vehicle_type_name}}</p>
                  <section class="vehicle-price">
                    <span class="vehicle-price-number">{{vehicle.price}}</span>
                    <span class="vehicle-price-suffix">shanix</span>
                  </section>
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
