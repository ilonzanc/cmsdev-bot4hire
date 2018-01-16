<template>
  <div id="create-vehicle">
	  	<div class="container">
			<h1>Nieuw voertuig</h1>
			<form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal_json" @submit.prevent="onSubmit">
				<label for="name">Naam</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.name.value">
				<label for="body">Beschrijving</label>
				<textarea id="body" name="body" placeholder="Beschrijving van voertuig" v-model="vehicle.description.value"></textarea>
				<label for="name">Prijs</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.price.value">
				<label for="name">Zitplaatsen</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.seats.value">
				<label for="name">Leeftijd</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.age.value">
				<label for="vehicle_type">Type</label>
				<select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type[0].target_id" >
					<option value="" selected>- Selecteer een type -</option>
					<option v-for="vehicle_type in vehicle_types" :value="vehicle_type.tid">{{vehicle_type.name}}</option>
				</select>
				<label for="places">Ophaallocatie</label>
				<select class="form-control" name="vehicle_type_id" v-model="vehicle.pickup_location[0].target_id" >
					<option selected value="">- Selecteer een plaats -</option>
					<option v-for="place in places" :value="place.tid">{{place.name}}</option>
				</select>
				<button type="submit" class="btn widebtn">Voertuig toevoegen</button>
			</form>
		</div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../utilities/Form.js';

export default {
  name: 'create-vehicle',
  data () {
    return {
		user: this.$parent.user,
		password: 'secret',
      	vehicle: {
			_links: {
				type: {
					href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/vehicle/vehicle"
				}
			},
			name: {
				value: ""
			},
			description: {
				value: ""
			},
			price: {
				value: ""
			},
			seats: {
				value: ""
			},
			age: {
				value: ""
			},
			vehicle_type:[{
				target_id :"",
				target_type: "taxonomy_term",
			}],
			pickup_location:[{
				target_id :"",
				target_type: "taxonomy_term",
			}]			
		},                 
		vehicle_types: {},
		places: {},
    }
  },
  	mounted() {
    	axios.get('http://localhost/cmsdev-bot4hire/drupal/api/vehicle_types')
			.then(response => {
			console.log(response)
			this.vehicle_types = response.data;
		});

		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/places')
			.then(response => {
			console.log(response)
			this.places = response.data;
		});
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal_json",
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
        data: self.vehicle
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
