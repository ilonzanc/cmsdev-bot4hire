<template>
  	<div id="home">
		<div class="hero__image">
			<router-link class="btn secundary-btn" to="/overzicht">Bekijk de catalogus</router-link>
		</div>
		<h2>Nieuwste voertuigen</h2>
		<div class="row">
			<div class="column column-sm-12 column-4" v-for="vehicle in vehicles">
				<section class="section__vehicle">
					<router-link :to='"overzicht/voertuig/" + vehicle.id'>
						<div class="row">						
							<div class="column column-sm-3 column-12"><img src="" :alt="vehicle.name + ' image'"></div>
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
		let self = this;
		axios({
		method: "get",
		url:
			"http://localhost/cmsdev-bot4hire/drupal/api/v1.0/vehicles?_format=hal_json",
		headers: {
			"X-CSRF-Token": self.user.csrf_token,
			Authorization: "Basic YWRtaW46c2VjcmV0"
		}
		})
		.then(function(response) {
			console.log(response);
			let latestThree = response.data.slice((response.data.length - 3),response.data.length);
			latestThree.reverse();
			self.vehicles = latestThree;
		})
		.catch(function(error) {
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
