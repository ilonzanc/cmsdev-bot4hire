<template>
  <div class="detail">
      <h1>{{vehicle.title}}</h1>
      <p>{{vehicle.body}}</p>
      <p>Eigenaar: {{vehicle.name}}</p>
      <h2>Specs</h2>
      <div>Kracht<div :class="'specbar specbarsize-' + vehicle.field_kracht"></div></div>
      <div>Snelheid<div :class="'specbar specbarsize-' + vehicle.field_snelheid"></div></div>
      <img :src='"http://localhost/" + vehicle.field_afbeelding'>
      <button class="btn">Huren</button>
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
      axios.get('http://localhost/cmsdev-bot4hire/drupal/api/vehicles/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.vehicle = response.data[0];
        });
    
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    .specbar {
        border: 1px solid black;
        display: inline-block;
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
