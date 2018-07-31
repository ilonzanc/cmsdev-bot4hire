<template>
  <div id="login">
    <div class="container">
      <div class="login-panel login-left">
        <h1>Welcome back</h1>
      </div>
      <div class="login-panel login-right">
        <header class="title-header">
          <!-- TODO: fix layout a bit -->
          <h1>Login to your account</h1>
          <svg version="1.1" id="title-line" x="0px" y="0px"
            viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
            <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
          </svg>
        </header>
        <form @submit.prevent="onSubmit">
          <label for="name">Username</label>
          <input type="text" id="name" name="name" placeholder="Your username..." required v-bind:class="{ 'filled-in': user.name }" v-model="user.name">
          <label for="pass">Password</label>
          <input type="password" id="pass"  name="pass" placeholder="********" required v-bind:class="{ 'filled-in': user.pass }" v-model="user.pass">
          <button type="submit" class="btn widebtn">login</button>
          <section class="form-bottom">
            <i>Don't have an account yet</i>
            <p><router-link to="/register">Register here!</router-link></p>
          </section>
        </form>
      </div>

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
