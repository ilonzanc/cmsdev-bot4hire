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
      <h2>Contract ID</h2>
      <div class="row">
        <div class="column column-sm-12 column-4">
          <section class="section__vehicle">
              <h3>Contract ID {{rental.id}}</h3>
              <p v-if="rental.accepted_by_owner == 0" class="message information">This contract still needs confirmation</p>
              <p>Vehicle rented: {{rental.vehicle_name}}</p>
              <p v-if="rental.renter_id != $parent.loggedInUser.current_user.name">Rented by: {{rental.renter_id}}</p>
              <p v-if="rental.vehicle_owner != $parent.loggedInUser.current_user.name">Owner: {{rental.renter_id}}</p>
              <button
                v-if="rental.vehicle_owner == $parent.loggedInUser.current_user.name && rental.accepted_by_owner == 0"
                class="btn"
                @click.prevent="acceptRental">
                Accept rental
              </button>
              <button
                v-if="rental.renter_id == $parent.loggedInUser.current_user.name && rental.accepted_by_owner == 1 && rental.returned_by_renter == 0"
                class="btn"
                @click.prevent="returnVehicle">
                Return vehicle
              </button>
              <button
                v-if="rental.vehicle_owner == $parent.loggedInUser.current_user.name &&
                rental.accepted_by_owner == 1 &&
                rental.returned_by_renter == 1 &&
                rental.return_confirmed_by_owner == 0"
                class="btn"
                @click.prevent="confirmReturn">
                Confirm return
              </button>
          </section>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "your-rental",
  data() {
    return {
      rental: {}
    }
  },
  mounted() {
    console.log('Rental Contracts Component Mounted');

    axios({
      method: "get",
      url: apiurl + "api/v1.0/rentals/" + this.$route.params.id + "?_format=hal_json",
      headers: {  }
    })
    .then((response) => {
      console.log(response);
      this.rental = response.data[0];
      this.rental.accepted_by_owner = parseInt(this.rental.accepted_by_owner);
      this.rental.return_confirmed_by_owner = parseInt(this.rental.return_confirmed_by_owner);
      this.rental.returned_by_renter = parseInt(this.rental.returned_by_renter);
    })
    .catch((error) => {
      console.log(error);
    });
  },
  methods: {
    acceptRental() {
      let acceptRentalData = {
        _links: {
          type: {
            href: apiurl + "rest/type/rental/rental"
          }
        },
        accepted_by_owner: {
          value: 1
        },
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/rental/" + this.$route.params.id + "?_format=hal_json",
        headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
      },
      auth: {
        username: this.$parent.loggedInUser.current_user.name,
        password: this.$parent.loggedInUser.current_user.password
      },
      data: acceptRentalData
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    },
    returnVehicle() {
      let returnTimestamp = moment().format("YYYY-MM-DD");
      console.log(returnTimestamp);

      let returnVehicleData = {
        _links: {
          type: {
            href: apiurl + "rest/type/rental/rental"
          }
        },
        returned_by_renter: {
          value: 1
        },
        return_date: {
          value: returnTimestamp
        }
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/rental/" + this.$route.params.id + "?_format=hal_json",
        headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
      },
      auth: {
        username: this.$parent.loggedInUser.current_user.name,
        password: this.$parent.loggedInUser.current_user.password
      },
      data: returnVehicleData
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    },
    confirmReturn() {
      let confirmReturnData = {
        _links: {
          type: {
            href: apiurl + "rest/type/rental/rental"
          }
        },
        return_confirmed_by_owner: {
          value: 1
        },
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/rental/" + this.$route.params.id + "?_format=hal_json",
        headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
      },
      auth: {
        username: this.$parent.loggedInUser.current_user.name,
        password: this.$parent.loggedInUser.current_user.password
      },
      data: confirmReturnData
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    },
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
