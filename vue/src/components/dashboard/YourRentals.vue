<template>
  <div id="your-vehicles">
    <div class="container">
      <header class="title-header">
        <h1>Your rental contracts</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 89" style="enable-background:new 0 0 250 89;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,85 40,85 25,69.9 0,69.9 "/>
        </svg>
      </header>
      <h2>As owner</h2>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="owner_rental.id" v-for="owner_rental in rentalsAsOwner">
          <section class="section__vehicle">
              <h3>Contract ID {{owner_rental.id}}</h3>
              <p v-if="owner_rental.accepted_by_owner == 0" class="message information">This contract still needs confirmation</p>
              <p v-if="owner_rental.returned_by_renter == 1 && owner_rental.return_confirmed_by_owner == 0" class="message information">This vehicle has been returned.</p>
              <p>Vehicle rented: {{owner_rental.vehicle_name}}</p>
              <p>Rented by: {{owner_rental.renter_id}}</p>
              <router-link :to="'rentalcontracts/' + owner_rental.id" class="btn smallbtn"><i class="fa fa-eye"></i> view</router-link>
          </section>
        </div>
      </div>
      <h2>As user</h2>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="user_rental.id" v-for="user_rental in rentalsAsUser">
          <section class="section__vehicle">
              <h3>Contract ID {{user_rental.id}}</h3>
              <p v-if="user_rental.accepted_by_owner == 0" class="message information">This contract still needs confirmation</p>
              <p>Vehicle rented: {{user_rental.vehicle_name}}</p>
              <p>Owner: {{user_rental.vehicle_owner}}</p>
              <router-link :to="'rentalcontracts/' + user_rental.id" class="btn smallbtn"><i class="fa fa-eye"></i> view</router-link>
          </section>
        </div>
      </div>
      <h2>Closed</h2>
      <div class="row">
        <div class="column column-sm-12 column-4" v-bind:key="closed_rental.id" v-for="closed_rental in closedRentals">
          <section class="section__vehicle">
              <h3>Contract ID {{closed_rental.id}}</h3>
              <p v-if="closed_rental.accepted_by_owner == 0" class="message information">This contract still needs confirmation</p>
              <p>Vehicle rented: {{closed_rental.vehicle_name}}</p>
              <p v-if="closed_rental.vehicle_owner != $parent.loggedInUser.current_user.name">Owner: {{closed_rental.vehicle_owner}}</p>
              <p v-if="closed_rental.renter_id != $parent.loggedInUser.current_user.name">Rented by: {{closed_rental.renter_id}}</p>
              <router-link :to="'rentalcontracts/' + closed_rental.id" class="btn smallbtn"><i class="fa fa-eye"></i> view</router-link>
          </section>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "your-rentals",
  data() {
    return {
      rentalsAsOwner: [],
      rentalsAsUser: [],
      closedRentals: []
    }
  },
  mounted() {
    console.log('Rental Contracts Component Mounted');

    axios({
      method: "get",
      url: apiurl + "api/v1.0/rentals/owner/" + this.$parent.loggedInUser.current_user.uid + "?_format=hal_json",
      headers: {  }
    })
    .then((response) => {
      console.log(response);
      this.rentalsAsOwner = response.data;

      let counter = 0;

      for (let rental of this.rentalsAsOwner) {
        rental.accepted_by_owner = parseInt(rental.accepted_by_owner);

        if (rental.return_confirmed_by_owner == 1) {
          this.closedRentals.push(rental);
          this.rentalsAsOwner.splice(counter, 1)
        }
        counter++;
      }
      console.log(counter);
    })
    .catch((error) => {
      console.log(error);
    });

    axios({
      method: "get",
      url: apiurl + "api/v1.0/users/" + this.$parent.loggedInUser.current_user.uid + "/rentals?_format=hal_json",
      headers: {  }
    })
    .then((response) => {
      console.log(response);
      this.rentalsAsUser = response.data;

      let counter = 0;

      for (let rental of this.rentalsAsUser) {
        rental.accepted_by_owner = parseInt(rental.accepted_by_owner);
        if (rental.return_confirmed_by_owner == 1) {
          this.closedRentals.push(rental);
          this.rentalsAsUser.splice(counter, 1)
        }
        counter++;
      }
    })
    .catch((error) => {
      console.log(error);
    });
  },
  methods: {
    closeToastMessage() {
      this.deleteVehicle.status = false;
      this.deleteVehicle.vehicle_id = null;
      this.deleteVehicle.vehicle_name = null;
    },
    onDelete(currentVehicle) {
      this.deleteVehicle.status = true;
      this.deleteVehicle.vehicle_id = currentVehicle.id;
      this.deleteVehicle.vehicle_name = currentVehicle.name;
    },
    confirmDelete() {
      // TODO: Replace hard delete with soft delete
      axios({
        method: 'delete',
        url: apiurl + "/admin/structure/vehicle/" + this.deleteVehicle.vehicle_id + "?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.password
        }
      })
      .then((response) => {
        console.log(response);
        this.$router.go();
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.data.message);
          let errorstring = error.response.data.message;
          errorstring = errorstring.replace(new RegExp('This value should not be null.', 'g'), 'This field is required!');
          let returnPos = errorstring.indexOf('\n');
          errorstring = errorstring.substr(returnPos + 1, errorstring.length);
          let errorarray = this.loopThroughErrors(errorstring);
          this.setErrors(errorarray);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
