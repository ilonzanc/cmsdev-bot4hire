<template>
  <div id="login">
	  	<div class="container">
			<h1>Aanmelden</h1>
			<form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/user/login?_format=hal_json" @submit.prevent="onSubmit">
				<label for="name">Gebruikersnaam</label>
				<input type="text" id="name" name="name" placeholder="Jouw gebruikersnaam..." v-model="user.name">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" v-model="user.pass">
				<button type="submit" class="btn widebtn">Inloggen</button>
				<p>Nog geen account?</p>
				<p><router-link to="/registreren">Maak hier een gratis account aan!</router-link></p>
			</form>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'login',
  data () {
    return {
      user: {
        name: "",
		pass: ""
      }, 
    }
  },
  mounted () {
	  console.log('Login Component Mounted');    
  },
  methods: {
	  onSubmit() {
			var self = this;
			axios({
				method: 'post',
				url: "http://localhost/cmsdev-bot4hire/drupal/user/login?_format=hal_json",
				headers: {
					'Accept': 'application/hal+json',
    				'Content-Type': 'application/hal+json',
					"X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
				},
				data: self.user
			})
			.then((response) => {
				console.log(response.data);
				localStorage.setItem('loggedInUser', JSON.stringify(response.data));
				location.href = '/profiel/' + response.data.current_user.uid;
			})
			.catch((error) => {
				console.log(error);
			});
		},
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
