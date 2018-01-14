<template>
  <div id="profile">
	  	<div class="container">
			<h1>{{user.current_user.name}}</h1>
			<router-link class="btn" to="/voertuig/nieuw">Nieuw voertuig toevoegen</router-link>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
  name: 'detail',
  data () {
    return {
      user: this.$parent.user
    }
  },
  mounted () {
	  console.log('Profile Component Mounted'); 
	  console.log(this.user);   
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
