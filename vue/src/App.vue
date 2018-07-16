<template>
  <div id="app">
    <header class="navigation-header">
        <div class="container">
            <a href="#" v-bind:class="'fa ' + [navOpen ? 'fa-times' : 'fa-bars'] + ' toggleBars'" @click.prevent="toggleNav"></a>
            <router-link to="/" exact><h1>Astrovia</h1></router-link>
            <router-link to="/" exact><img class="header_logo" src=""><h1></h1></router-link>
            <nav :class="{ 'nav-open': navOpen }">
                <ul>
                    <router-link to="/" tag="li" exact @click.prevent="toggleNav">
                        <a>Home</a>
                    </router-link>
                    <router-link to="/overzicht" tag="li" exact @click.prevent="toggleNav">
                        <a>Overzicht</a>
                    </router-link>
                    <router-link v-if="!loggedInUser" to="/aanmelden" tag="li" exact @click.prevent="toggleNav">
                        <a>Aanmelden</a>
                    </router-link>
                    <div v-if="loggedInUser">
                        <router-link :to="'/profiel/' + loggedInUser.current_user.uid" tag="li" exact @click.prevent="toggleNav">
                            <a>Profiel</a>
                        </router-link>
                        <router-link :to="'/dashboard'" tag="li" exact @click.prevent="toggleNav">
                            <a>Dashboard</a>
                        </router-link>
                        <li @click.prevent="logOut">
                          <a href="">Uitloggen</a>
                        </li>
                    </div>
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
      loggedInUser: JSON.parse(localStorage.getItem('loggedInUser')),
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
      location.href = '/';
    }
  }
}
</script>

<style type="text/css" src="./assets/css/main.css"></style>
