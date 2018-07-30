<template>
  <div id="your-vehicles">
    <div class="container">
      <header class="title-header">
        <!-- TODO: not same layout as overview. More dashboard-y... -->
        <h1>Your vehicles</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 89" style="enable-background:new 0 0 250 89;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="vehicle.id" v-for="vehicle in vehicles">
          <section class="section__vehicle">
              <div class="row">
                <div class="column column-sm-3 column-12">
                  <div class="image-border">
                    <div class="vehicle__image" :style='"background: url( http://localhost:8888" + vehicle.image_url + ") no-repeat center; background-size: contain"'></div>
                  </div>
                </div>
                <div class="column column-sm-9 column-12">
                  <!-- TODO: more user-friendly buttons -->
                  <router-link :to="'vehicles/' + vehicle.id"><i class="fa fa-eye"></i></router-link>
                  <router-link :to="'vehicles/' + vehicle.id + '/edit'"><i class="fa fa-pencil"></i></router-link>
                  <i @click.prevent="onDelete(vehicle)" class="fa fa-trash"></i>
                  <h2>{{vehicle.name}}</h2>
                  <p>{{vehicle.vehicle_type_name}}</p>
                  <section class="vehicle-price">
                    <span class="vehicle-price-number">{{vehicle.price}}</span>
                    <span class="vehicle-price-suffix">shanix</span>
                  </section>
                </div>
              </div>
          </section>
        </div>
      </div>
      <article v-if="deleteVehicle.status" class="toast-message">
        <!-- TODO: Fancy slide in animation with darker overlay -->
        <i @click.prevent="closeToastMessage()" class="fa fa-times"></i>
        <p>Are you sure you want to erase <em>{{deleteVehicle.vehicle_name}}</em> from the system?</p>
        <button @click.prevent="confirmDelete()" class="btn delete-btn">Erase</button>
        <button @click.prevent="closeToastMessage()" class="btn">Cancel</button>
      </article>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "your-vehicles",
  data() {
    return {
      user: this.$parent.user,
      password: this.$parent.user_password,
      vehicles: [],
      deleteVehicle: {
        status: false,
        vehicle_id: null,
        vehicle_name: null
      },
      errors: {

      }
    }
  },
  mounted() {
    axios({
      method: "get",
      url: apiurl + "api/v1.0/users/" + this.$parent.loggedInUser.current_user.uid + "/vehicles?_format=hal_json",
      headers: {  }
    })
    .then((response) => {
      console.log(response);
      this.vehicles = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
  },
  methods: {
    closeToastMessage() {
      this.deleteVehicle.status = false;
      this.deleteVehicle.vehicle_id = null;
      this.deleteVehicle.vehicle_name = null;
    },
    onDelete(currentVehicle) {
      this.deleteVehicle.status = true;
      this.deleteVehicle.vehicle_id = currentVehicle.id;
      this.deleteVehicle.vehicle_name = currentVehicle.name;
    },
    confirmDelete() {
      // TODO: Replace hard delete with soft delete
      axios({
        method: 'delete',
        url: apiurl + "/admin/structure/vehicle/" + this.deleteVehicle.vehicle_id + "?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.password
        }
      })
      .then((response) => {
        console.log(response);
        this.$router.go();
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.data.message);
          let errorstring = error.response.data.message;
          errorstring = errorstring.replace(new RegExp('This value should not be null.', 'g'), 'This field is required!');
          let returnPos = errorstring.indexOf('\n');
          errorstring = errorstring.substr(returnPos + 1, errorstring.length);
          let errorarray = this.loopThroughErrors(errorstring);
          this.setErrors(errorarray);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
