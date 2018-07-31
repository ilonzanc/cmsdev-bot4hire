<template>
  <div id="vehicle-overview">
    <div class="container">
      <!-- TODO: add search and filters -->
      <header class="title-header">
        <h1>Overview</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="vehicle.id" v-for="vehicle in vehicles">
          <section class="section__vehicle">
            <router-link :to='"overview/vehicle/" + vehicle.id'>
              <div class="image-border">
                <!-- TODO: resize images depending on portrait/landscape -->
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
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "overview",
  data() {
    return {
    user: this.$parent.user,
    password: this.$parent.user_password,
        vehicles: []
    };
  },
  mounted() {
    let self = this;
    axios({
      method: "get",
      url:
        apiurl + "api/v1.0/vehicles?_format=hal_json",
      headers: {
      }
    })
      .then(function(response) {
        console.log(response);
        self.vehicles = response.data;
      })
      .catch(function(error) {
        console.log(error);
      });
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
