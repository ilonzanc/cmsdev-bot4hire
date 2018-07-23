<template>
  <div class="create-vehicle">
      <div class="container">
      <header class="title-header">
        <h1>Rent vehicle</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 89" style="enable-background:new 0 0 250 89;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,85 40,85 25,69.9 0,69.9 "/>
        </svg>
      </header>
      <h2>Voertuig Details</h2>
      <p>Naam: {{vehicle.name}}</p>
      <p>Type: {{vehicle.vehicle_type_name}}</p>
      <p>Eigenaar: {{vehicle.user_name}}</p>
      <h2>Huur Details</h2>
      <form @submit.prevent="onSubmit">
        <label for="start_date">Start datum</label>
        <input type="date" min="1900-01-01" max="2050-12-31" data-drupal-date-format="Y-m-d" id="start_date" required name="start_date" value="2018-01-15" size="12" v-bind:class="{ 'filled-in': rental_details.start_date.value }" v-model="rental_details.start_date.value">
        <label for="end_date">End datum</label>
        <input type="date" min="1900-01-01" max="2050-12-31" data-drupal-date-format="Y-m-d" id="end_date" name="end_date" required value="2018-01-22" size="12" v-bind:class="{ 'filled-in': rental_details.end_date.value }" v-model="rental_details.end_date.value">
        <button type="submit" class="btn widebtn">Send rent request</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../utilities/Form.js';

export default {
  name: 'rent-vehicle',
  data () {
    return {
    vehicle: { },
    rental_details: {
      _links: {
        type: {
          href: apiurl + "rest/type/rental/rental"
        }
      },
      name: {
        value: ''
      },
      end_date: {
        value: ""
      },
      start_date: {
        value: ""
      },
      vehicle_id:[{
        target_id: "",
        target_type:"vehicle"
      }]
    },
    }
  },
  mounted() {
      axios.get(apiurl + 'api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
      .then(response => {
        console.log(response.data[0])
        this.vehicle = response.data[0];
        let vehiclename = this.vehicle.name.toLowerCase();
        vehiclename = vehiclename.split(' ').join('_');
        this.rental_details.name.value = vehiclename;
        this.rental_details.vehicle_id[0].target_id = this.vehicle.id;
      })
      .catch(error => {
        console.log(error);
      });
  },
  methods: {
    onSubmit() {
      axios({
        method: 'post',
        url: apiurl + "entity/rental?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
      auth: {
        username: this.$parent.loggedInUser.current_user.name,
        password: this.$parent.loggedInUser.current_user.pass
      },
      data: this.rental_details
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
