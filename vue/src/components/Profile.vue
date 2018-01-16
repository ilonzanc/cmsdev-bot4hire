<template>
  <div id="profile">
	  	<div class="container">
			<section class="section__profile">
				<i class="fa fa-user-circle"></i>
				<h1>{{user.name[0].value}}</h1>
			</section>			
			<section class="section__vehicles-list">
				<h2>Voertuigen</h2>
				<div class="row">
					<div class="column column-sm-12 column-4" v-for="vehicle in vehicles">
						<section class="section__vehicle">
							<router-link :to='"/overzicht/voertuig/" + vehicle.id'>
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
				<router-link v-if="activeuser.current_user.uid == user.uid[0].value" class="btn" to="/voertuig/nieuw">Nieuw voertuig toevoegen</router-link>
			</section>			
			<h2>Lopende huur</h2>
			<section class="section__rentals">
				<div class="row">
					<div class="column column-sm-12 column-4" v-for="rental in rentals">
						<section class="section__vehicle">
							<router-link :to='"/overzicht/voertuig/" + rental.vehicle_id'>
								<div class="row">						
									<div class="column column-sm-3 column-12">
										<div class="vehicle__image" :style='"background: url(" + rental.image + ") no-repeat center; background-size: contain"'></div>
									</div>
									<div class="column column-sm-9 column-12">
										<p>{{rental.vehicle_id}}</p>
										<p>{{rental.start_date}} <i class="fa fa-arrow-right"></i> {{rental.end_date}}</p>
									</div>						
								</div>      
							</router-link>
							<section v-if="activeuser.current_user.name == rental.vehicle_user_username" class="owner__buttons">
							<button @click="deleteRental(rental.id)" class="btn">Verwijderen</button>
						</section> 
						</section>
						   					
					</div>
				</div>
				<p v-if="rentals.length == 0">Deze gebruiker huurt op dit moment geen voertuigen.</p>
			</section>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
  	name: 'detail',
  	data () {
		return {
			activeuser: this.$parent.user,
			password: this.$parent.user_password,
			user: {},
			vehicles: [],
			rentals: [],
			password: 'secret'
		}
  },
  mounted () {

	  	let self = this;
		console.log('Profile Component Mounted');

		axios({
        method: 'get',
        url: "http://localhost/cmsdev-bot4hire/drupal/user/" + this.$route.params.id + "?_format=hal_json",
        headers: {
			//'X-CSRF-Token': self.user.csrf_token,
			'Accept': 'application/hal+json',
			'Content-Type': 'application/hal+json',
			'X-CSRF-Token': self.activeuser.csrf_token,
		},
		auth: {
			username: self.activeuser.current_user.name,
			password: "secret"
		},
	  })
	  .then(response => {
			console.log(response)
			self.user = response.data;
			this.getVehiclesByUser();
		})
		.catch(error => {
			console.log(error);
		});  
		
		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/user/' + this.$route.params.id + '/rentals?_format=hal_json')
		.then(response => {
			console.log(response.data[0])
			this.rentals = response.data;
		})
		.catch(error => {
			console.log(error);
		}); 
				
  },
  methods: {
	  getVehiclesByUser() {
		  axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/vehicles?_format=hal_json')
			.then(response => {
				console.log(response)
				for (let i = 0; i < response.data.length; i++){
					if (response.data[i].user_id == this.user.uid[0].value)
						this.vehicles.push(response.data[i])
				}
			})
			.catch(error => {
				console.log(error);
			});
	  },
	  deleteRental(rental_id) {
		  let self = this;
		  axios({
			method: 'delete',
			url: "http://localhost/cmsdev-bot4hire/drupal/admin/structure/rental/" + rental_id + "?_format=hal_json",
			headers: {
				//'X-CSRF-Token': self.user.csrf_token,
				'Accept': 'application/hal+json',
				'Content-Type': 'application/hal+json',
				'X-CSRF-Token': self.activeuser.csrf_token,
			},
			auth: {
				username: self.activeuser.current_user.name,
				password: self.password
			}
		}).then(function (response) {
			console.log(response);
			location.reload();
		}).catch(function(error) {
			console.log(error);
		});
	  }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    .specbar {
        border: 1px solid black;
        display: block;
        height: 30px;
    }

    .specbarsize-1 {
        width: 30px;    
    } 

    .specbarsize-2 {
        width: 60px;    
    } 

    .specbarsize-3 {
        width: 90px;    
    } 

    .specbarsize-4 {
        width: 120px;    
    } 

    .specbarsize-5 {
        width: 150px;    
    } 
</style>
