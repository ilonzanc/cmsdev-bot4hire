<template>
  <div class="create-vehicle">
	  	<div class="container">
			<h1>Voertuig huren</h1>
			<h2>Voertuig Details</h2>
			<p>Naam: {{vehicle.name}}</p>
			<p>Type: {{vehicle.field_vehicle_type}}</p>
			<p>Eigenaar: {{vehicle.user_id}}</p>
			<h2>Huur Details</h2>
			<form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/entity/rental?_format=hal_json" @submit.prevent="onSubmit">
				<label for="start_date">Start datum</label>
				<input type="date" min="1900-01-01" max="2050-12-31" data-drupal-date-format="Y-m-d" id="start_date" name="start_date" value="2018-01-15" size="12" v-model="rental_details.field_start_date.value">
				<label for="end_date">End datum</label>
				<input type="date" min="1900-01-01" max="2050-12-31" data-drupal-date-format="Y-m-d" id="end_date" name="end_date" value="2018-01-22" size="12" v-model="rental_details.field_end_date.value">
				<button type="submit" class="btn widebtn">Huur bevestigen</button>
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
		user: this.$parent.user,
		password: 'secret',
      	vehicle: { },                 
		vehicle_types: { },
		rental_details: {
			_links: {
				type: {
					href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/rental/rental"
				}
			},
			name: {
				value: ''
			},
			field_end_date: {
                value: ""
			},
			field_start_date: {
                value: ""
			},
			field_rented_vehicle: {
				target_id: 17,
			}
		},
    }
  },
  mounted() {
	  	axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
			.then(response => {
				console.log(response.data[0])
				this.vehicle = response.data[0];
				let vehiclename = this.vehicle.name.toLowerCase();
				vehiclename = vehiclename.split(' ').join('_');
				this.rental_details.name.value = vehiclename;
				this.rental_details.field_rented_vehicle.target_id = this.vehicle.id;
			})
			.catch(error => {
				console.log(error);
			});

		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/vehicle_types')
			.then(response => {
				console.log(response)
				this.vehicle_types = response.data;
			});
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost/cmsdev-bot4hire/drupal/entity/rental?_format=hal_json",
        headers: {
			//'X-CSRF-Token': self.user.csrf_token,
			'Accept': 'application/hal+json',
			'Content-Type': 'application/hal+json',
			'X-CSRF-Token': self.user.csrf_token,
		},
		auth: {
			username: self.user.current_user.name,
			password: self.password
		},
        data: self.rental_details
      }).then(function (response) {
        console.log(response);
      }).catch(function(error) {
        console.log(error);
      });
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
