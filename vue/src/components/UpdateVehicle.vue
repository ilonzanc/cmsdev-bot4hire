<template>
  <div id="update-vehicle">
	  	<div class="container">
			<h1>Voertuig bewerken</h1>
			<form method="POST" :action="'http://localhost/cmsdev-bot4hire/drupal/admin/structure/vehicle/'+ this.$route.params.id + '?_format=hal_json'" @submit.prevent="onSubmit">

				<label for="name">Naam</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.name">
				<label for="body">Beschrijving</label>
				<textarea id="body" name="body" placeholder="Beschrijving van voertuig" v-model="vehicle.description"></textarea>
				<label for="name">Prijs</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.price">
				<label for="name">Zitplaatsen</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.seats">
				<label for="name">Leeftijd</label>
				<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.age">
				<label for="vehicle_type">Type</label>
				<select class="form-control" name="vehicle_type" v-model="vehicle.tid_1" >
					<option value="" selected>- Selecteer een type -</option>
					<option v-for="vehicle_type in vehicle_types" :value="vehicle_type.tid">{{vehicle_type.name}}</option>
				</select>
				<label for="places">Ophaallocatie</label>	
				<select class="form-control" name="vehicle_type_id" v-model="vehicle.tid" >
					<option selected value="">- Selecteer een plaats -</option>
					<option v-for="place in places" :value="place.tid">{{place.name}}</option>
				</select>
				<button type="submit" class="btn widebtn">Voertuig bewerken</button>
			</form>
		</div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../utilities/Form.js';

export default {
  name: 'update-vehicle',
  data () {
    return {
		user: this.$parent.user,
		password: this.$parent.user_password,
		vehicle: {},
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

		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
		.then(response => {
			console.log(response)
			this.vehicle = response.data[0];
		})
		.catch(error => {
			console.log(error);
		});
  },
  methods: {
    onSubmit() {
	  var self = this;
	  console.log(self.vehicle.name);
	  let updateVehicle = {
		  _links: {
				type: {
					href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/vehicle/vehicle"
				}
			},
			name: {
				value: self.vehicle.name
			},
			description: {
				value: self.vehicle.description
			},
			price: {
				value: self.vehicle.price
			},
			seats: {
				value: self.vehicle.seats
			},
			age: {
				value: self.vehicle.age
			},
			vehicle_type:[{
				target_id: self.vehicle.tid_1,
				target_type: "taxonomy_term",
			}],
			pickup_location:[{
				target_id: self.vehicle.tid,
				target_type: "taxonomy_term",
			}]	
	  };
      axios({
        method: 'patch',
        url: "http://localhost/cmsdev-bot4hire/drupal/admin/structure/vehicle/" + this.$route.params.id + "?_format=hal_json",
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
        data: updateVehicle
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
