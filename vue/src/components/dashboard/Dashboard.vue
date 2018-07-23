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
        <router-link to="/dashboard/rentalcontracts" class="btn">Manage your rental contracts<i v-if="gotRentalNotifications == true" class="fa fa-envelope"></i></router-link>
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
      vehicles: [],
      rentals: [],
      gotRentalNotifications: false
    }
  },
  mounted () {
    console.log('Dashboard Component Mounted');

    axios.get(apiurl + 'api/v1.0/rentals/owner/' + this.$parent.loggedInUser.current_user.uid + '?_format=hal_json')
    .then(response => {
      console.log(response.data[0])
      this.rentals = response.data;

      for (let rental of this.rentals) {
        console.log(rental);
        rental.accepted_by_owner = parseInt(rental.accepted_by_owner);
        rental.returned_by_renter = parseInt(rental.returned_by_renter);
        rental.return_confirmed_by_owner = parseInt(rental.return_confirmed_by_owner);

        if (rental.accepted_by_owner == 0) {
          this.gotRentalNotifications = true;
        } else if (rental.returned_by_renter == 1 && rental.return_confirmed_by_owner == 0) {
          this.gotRentalNotifications = true;
        }
      }
    })
    .catch(error => {
      console.log(error);
    });

  },
  methods: {
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
