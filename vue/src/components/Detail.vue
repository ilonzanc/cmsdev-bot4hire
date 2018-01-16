<template>
  <div id="detail">
	  	<div class="container">
			<router-link class="breadcrumbs" to="/overzicht"><i class="fa fa-chevron-left"></i> terug naar overzicht</router-link>
			<div class="row"></div>
			<div class="column column-sm-12 column-6">
				<img :src='"http://localhost/" + vehicle.field_afbeelding'>
			</div>
			<div class="column column-sm-12 column-6">
				<h1>{{vehicle.name}}</h1>
				<p>{{vehicle.field_description}}</p>
				<p>Eigenaar: <router-link :to="'/profiel/' + vehicle.uid">{{vehicle.user_id}}</router-link></p>
				<h2>Specs</h2>
				<div><span>Kracht</span><div :class="'specbar specbarsize-2 ' + vehicle.field_kracht"></div></div>
				<div><span>Snelheid</span><div :class="'specbar specbarsize-3 ' + vehicle.field_snelheid"></div></div>			
				<router-link :to="vehicle.id + '/huren'" class="btn">Huren</router-link>
			</div>
			<h2>Reviews</h2>
			<section v-for="review in reviews" class="section__review">
				<div class="row">
					<div class="column column-sm-4">
						<img class="user_profile">
						<p>{{review.user_username}}</p>
					</div>
					<div class="column column-sm-8">
						<h3>{{review.title}}</h3>
						<p>{{review.rating}}</p>
						<p>{{review.body}}</p>
					</div>
				</div>
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
	  vehicle: {},
	  reviews: []
    }
  },
  	mounted () {
	  	console.log('Detail Component Mounted');
		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
		.then(response => {
			console.log(response.data[0])
			this.vehicle = response.data[0];
		})
		.catch(error => {
			console.log(error);
		});

		axios.get('http://localhost/cmsdev-bot4hire/drupal/api/v1.0/reviews?_format=hal_json')
		.then(response => {
			console.log(response.data[0])
			this.reviews = response.data;
		})
		.catch(error => {
			console.log(error);
		});
    
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
