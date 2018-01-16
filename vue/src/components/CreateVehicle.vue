<template>
  <div class="create-vehicle">
	  <div class="container"></div>
		<h1>Nieuw voertuig</h1>
		<form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal_json" @submit.prevent="onSubmit">
			<label for="name">Naam</label>
			<input type="text" id="name" name="name" placeholder="Naam van je voertuig" v-model="vehicle.name[0].value">
			<label for="pass">Wachtwoord</label>
			<input type="password" id="pass" name="pass" placeholder="********" v-model="password">
			<label for="body">Beschrijving</label>
			<!-- <textarea id="body" name="body" placeholder="Beschrijving van voertuig" v-model="form.body"></textarea>
			<select class="form-control" name="party_id" v-model="form.tid" >
				<option value="">- Selecteer een type -</option>
				<option v-for="vehicle_type in vehicle_types" :value="vehicle_type.tid">{{vehicle_type.name}}</option>
			</select> -->
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
		password: '',
      	vehicle: {
			_links: {
				type: {
					href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/vehicle/vehicle"
				}
			},
			name: [{
				value: ""
			}],
      },                 
      vehicle_types: {},
    }
  },
  	mounted() {
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
