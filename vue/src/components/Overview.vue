<template>
  <div id="overview__comp">
		<div class="container">
			<h1>Overzicht</h1>
			<div class="row">
				<div class="column column-sm-12 column-4" v-for="vehicle in vehicles">
					<section class="section__vehicle">
						<router-link :to='"overzicht/voertuig/" + vehicle.id'>
							<div class="row">						
								<div class="column column-sm-3 column-12"><img src="" :alt="vehicle.name + ' image'"></div>
								<div class="column column-sm-9 column-12">
									<i class="fa fa-angle-right"></i>
									<h2>{{vehicle.name}}</h2>
									<p>{{vehicle.field_vehicle_type}}</p>
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
import axios from "axios";

export default {
  name: "overview",
  data() {
    return {
		user: this.$parent.user,
      	vehicles: []
    };
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
