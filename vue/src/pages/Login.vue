<template>
  <div id="login">
      <div class="container">
      <header class="title-header">
        <!-- TODO: fix layout a bit -->
        <h1>Login</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <form @submit.prevent="onSubmit">
        <label for="name">Gebruikersnaam</label>
        <input type="text" id="name" name="name" placeholder="Jouw gebruikersnaam..." required v-bind:class="{ 'filled-in': user.name }" v-model="user.name">
        <label for="pass">Wachtwoord</label>
        <input type="password" id="pass" name="pass" placeholder="********" required v-bind:class="{ 'filled-in': user.pass }" v-model="user.pass">
        <button type="submit" class="btn widebtn">login</button>
        <p>Nog geen account?</p>
        <p><router-link to="/register">Maak hier een gratis account aan!</router-link></p>
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
      axios({
        method: 'post',
        url: apiurl + "user/login?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
        },
        data: this.user
      })
      .then((response) => {
        console.log(response.data);
        response.data.current_user.pass = this.user.pass;
        localStorage.setItem('loggedInUser', JSON.stringify(response.data));
        location.href = '/profile/' + response.data.current_user.uid;
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
