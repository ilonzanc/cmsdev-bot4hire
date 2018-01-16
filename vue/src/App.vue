<template>
  <div id="app">
    <header>
        <div class="container">
            <a href="#" v-bind:class="'fa ' + [navOpen ? 'fa-times' : 'fa-bars'] + ' toggleBars'" @click.prevent="toggleNav"></a>
			<h1>Bot4Hire</h1>
            <router-link to="/" exact><img class="header_logo" src=""><h1></h1></router-link>
            <nav :class="{ 'nav-open': navOpen }">
                <ul>
                    <router-link to="/" tag="li" exact @click.prevent="toggleNav">
                        <a>Home</a>
                    </router-link>
                    <router-link to="/overzicht" tag="li" exact @click.prevent="toggleNav">
                        <a>Overzicht</a>
                    </router-link>
					<router-link v-if="!user" to="/aanmelden" tag="li" exact @click.prevent="toggleNav">
                        <a>Aanmelden</a>
                    </router-link>
					<router-link v-if="user" :to="'/profiel/' + user.current_user.uid" tag="li" exact @click.prevent="toggleNav">
                        <a>Profiel</a>
                    </router-link>
					<li v-if="user" exact @click.prevent="logOut">
                        <a>Uitloggen</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
      	<router-view></router-view>
    </main>
  </div>
</template>

<script>
import axios from "axios";

export default {
	name: 'app',
	data() {
		return {
			navOpen: false,
			user: JSON.parse(localStorage.getItem('loggedInUser')),
			user_password: localStorage.getItem('password')
		}    
	},
	watch: {
    	$route() {
    	    this.navOpen = false;
    	}
	},
  	methods: {
      	toggleNav() {
          	if (this.navOpen == false) {
              	this.navOpen = true;
          	}
          	else {
              	this.navOpen = false;
          	}
      	},
      	closeNav() {
          this.navOpen = false;
		
		},
		logOut() {
			localStorage.removeItem('loggedInUser');
			localStorage.removeItem('password');
			location.href = '/';
		}
	}
}
</script>

<style type="text/css" src="./assets/css/main.css"></style>
