<template>
  <div id="dashboard">
    <ul class="action-icons">
      <li><i class="fa fa-plus"></i></li>
      <li><h1>a</h1></li>
      <li><i class="fa fa-envelope-o"></i></li>
    </ul>
    <div class="container">
      <header class="title-header">
        <h1>Dashboard</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <section class="actions">
        <h2>What do you want to do?</h2>
        <router-link to="/dashboard/vehicles/new" class="btn">Add a new vehicle</router-link>
        <router-link to="/dashboard/vehicles" class="btn">Manage your vehicles</router-link>
        <router-link to="/dashboard/profile/edit" class="btn">Edit your profile</router-link>
        <router-link to="/dashboard/rental-contracts" class="btn">Manage your rental contracts</router-link>
      </section>
      <section class="statistics">
        <h2>Statistics</h2>
        <section class="stat-top">
          <article class="stat-numbers"><h3>Current available vehicles</h3></article>
          <article class="stat-numbers"><h3>Current rented out vehicles</h3></article>
          <article class="stat-numbers"><h3>Most popular vehicle</h3></article>
        </section>
        <article class="stat-graph"><h3>Rented vehicles this year</h3></article>
      </section>
    </div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
    name: 'dashboard',
    data () {
    return {
      activeuser: this.$parent.user,
      password: this.$parent.user_password,
      user: {},
      vehicles: [],
      rentals: [],
      password: 'secret'
    }
  },
  mounted () {
    console.log('Profile Component Mounted');
    axios({
      method: 'get',
      //url: apiurl + "user/" + this.$route.params.id + "?_format=hal_json",
      url: apiurl + "api/v1.0/users/" + this.$route.params.id + "?_format=hal_json",
      headers: {
      //'X-CSRF-Token': this.user.csrf_token,
      'Accept': 'application/hal+json',
      'Content-Type': 'application/hal+json',
      'X-CSRF-Token': this.activeuser.csrf_token,
      },
      auth: {
        username: this.activeuser.current_user.name,
        password: "secret"
      },
    })
    .then(response => {
      console.log(response)
      this.user = response.data[0];
      let slashPos = this.user.uri.indexOf("/");
      console.log(slashPos);
      this.user.uri = this.user.uri.substring(slashPos + 1, this.user.uri.length);
      this.getVehiclesByUser();
    })
    .catch(error => {
      console.log(error);
    });

    axios.get(apiurl + 'api/v1.0/user/' + this.$route.params.id + '/rentals?_format=hal_json')
    .then(response => {
      console.log(response.data[0])
      this.rentals = response.data;
    })
    .catch(error => {
      console.log(error);
    });

  },
  methods: {
    getVehiclesByUser() {
      axios.get(apiurl + 'api/v1.0/vehicles?_format=hal_json')
      .then(response => {
        console.log(response)
        for (let i = 0; i < response.data.length; i++){
          if (response.data[i].user_id == this.user.uid[0].value)
            this.vehicles.push(response.data[i])
        }
      })
      .catch(error => {
        console.log(error);
      });
    },
    deleteRental(rental_id) {
      axios({
      method: 'delete',
      url: apiurl + "admin/structure/rental/" + rental_id + "?_format=hal_json",
      headers: {
        //'X-CSRF-Token': this.user.csrf_token,
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.activeuser.csrf_token,
      },
      auth: {
        username: this.activeuser.current_user.name,
        password: this.password
      }
    }).then((response) => {
      console.log(response);
      location.reload();
    }).catch((error) => {
      console.log(error);
    });
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    dt, dd {
      float: left;

    }
    dt {
      clear: left;
    }
</style>
