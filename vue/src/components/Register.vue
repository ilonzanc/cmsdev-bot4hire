<template>
  <div id="register">
	  	<div class="container">
			<h1>Registreren</h1>
			<form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/user/register?_format=hal_json" @submit.prevent="onSubmit">
				<label for="name">Username</label>
				<input type="text" id="name" name="name" placeholder="Jouw gebruikersnaam..." v-model="user.name.value">
				<label for="mail">Emailadres</label>
				<input type="email" id="mail" name="mail" placeholder="Jouw emailadres..." v-model="user.mail.value">
				<label for="pass">Wachtwoord</label>
				<input type="password" id="pass" name="pass" placeholder="********" v-model="user.pass.value">
				<button type="submit" class="btn widebtn">Registreren</button>
			</form>
	  	</div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'register',
	data () {
		return {
			user: {
				_links: {
					type: {
						href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/user/user"
					}
				},
				name:{value:""},
				mail:{value:""},
				pass:{value:""}
			}, 
		}
	},
	mounted () {
		console.log('Register Component Mounted');    
	},
	methods: {
		onSubmit() {
			var self = this;
			axios({
				method: 'post',
				url: "http://localhost/cmsdev-bot4hire/drupal/user/register?_format=hal_json",
				headers: {
					'Accept': 'application/hal+json',
    				'Content-Type': 'application/hal+json',
					"X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
				},
				data: self.user
			})
			.then((response) => {
				console.log(response.data);
				/* localStorage.setItem('loggedInUser', JSON.stringify(response.data));
				location.href = '/profiel/' + response.data.current_user.uid; */
				self.loginUser();
			})
			.catch((error) => {
				console.log(error);
			});
		},
		loginUser() {
			let registeredUser = {
				name: this.user.name.value,
				pass: this.user.pass.value
			}
			axios({
				method: 'post',
				url: "http://localhost/cmsdev-bot4hire/drupal/user/login?_format=hal_json",
				headers: {
					'Accept': 'application/hal+json',
    				'Content-Type': 'application/hal+json',
					"X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
				},
				data: registeredUser
			})
			.then((response) => {
				console.log(response.data);
				localStorage.setItem('loggedInUser', JSON.stringify(response.data));
				localStorage.setItem('password', registeredUser.pass);
				location.href = '/profiel/' + response.data.current_user.uid;
			})
			.catch((error) => {
				console.log(error);
			});
		}
	}
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
