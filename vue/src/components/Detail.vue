<template>
  <div id="detail">
	  	<div class="container">
			<router-link class="breadcrumbs" to="/overzicht">Terug naar overzicht</router-link>
			<h1>{{vehicle.name[0].value}}</h1>
			<p>{{vehicle.field_description[0].value}}</p>
			<p>Eigenaar: <router-link to="/profiel/id">Admin</router-link></p>
			<h2>Specs</h2>
			<div><span>Kracht</span><div :class="'specbar specbarsize-2 ' + vehicle.field_kracht"></div></div>
			<div><span>Snelheid</span><div :class="'specbar specbarsize-3 ' + vehicle.field_snelheid"></div></div>
			<img :src='"http://localhost/" + vehicle.field_afbeelding'>
			<button class="btn">Huren</button>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'detail',
  data () {
    return {
      vehicle: {}
    }
  },
  mounted () {
	  console.log('Detail Component Mounted');
	axios.get('http://localhost/cmsdev-bot4hire/drupal/admin/structure/vehicle/' + this.$route.params.id + '?_format=hal_json')
		.then(response => {
			console.log(response)
			this.vehicle = response.data;
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
