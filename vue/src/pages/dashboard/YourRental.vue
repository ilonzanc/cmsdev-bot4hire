<template>
  <div id="your-vehicles">
    <div class="container">
      <router-link class="breadcrumbs" to="/dashboard/rentalcontracts"><i class="fa fa-chevron-left"></i> back to rental overview</router-link>
      <header class="title-header">
        <h1>Your rental contracts</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <h2>Contract ID</h2>
      <div class="row">
        <div class="column column-sm-12 column-4">
          <section class="section__vehicle">
              <h3>Contract ID {{rental.id}}</h3>
              <p v-if="rental.accepted_by_owner == 0" class="message information">This contract still needs confirmation</p>
              <p>Vehicle rented: {{rental.vehicle_name}}</p>
              <p v-if="rental.renter_username != $parent.loggedInUser.current_user.name">Rented by: {{rental.renter_username}}</p>
              <p v-if="rental.vehicle_owner != $parent.loggedInUser.current_user.name">Owner: {{rental.vehicle_owner}}</p>
              <button
                v-if="rental.vehicle_owner == $parent.loggedInUser.current_user.name && rental.accepted_by_owner == 0"
                class="btn"
                @click.prevent="acceptRental">
                Accept rental
              </button>
              <button
                v-if="rental.renter_username == $parent.loggedInUser.current_user.name && rental.accepted_by_owner == 1 && rental.returned_by_renter == 0"
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
      <section v-if="rental.return_confirmed_by_owner == 1" class="section__newreview">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <h2><i class="fa fa-plus"></i> Add a new review</h2>
            <form @submit.prevent="postReview">
              <label for="review_type">Review type *</label>
              <select class="form-control" name="review_type" v-model="newReview.review_type[0].target_id" >
                <option disabled selected value="">- Select review type -</option>
                <option v-for="review_type in review_types" v-bind:key="review_type.tid" :value="review_type.tid">{{review_type.name}}</option>
              </select>
              <label for="title">Title *</label>
              <input type="text" id="title" name="title" placeholder="Title of your review..." ref="title" v-bind:class="{ 'filled-in': newReview.title.value }" v-model="newReview.title.value">
              <label for="rating">Rating *</label>
              <input type="text" name="rating" placeholder="Rating on 5..." v-bind:class="{ 'filled-in': newReview.title.value }" v-model="newReview.rating.value">
              <label for="body">Description</label>
              <textarea id="body" name="body" placeholder="Tell us more..." v-model="newReview.body.value"></textarea>
              <button type="submit" class="btn">add review</button>
            </form>
          </div>
        </div>
      </section>
    </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "your-rental",
  data() {
    return {
      rental: {},
      newReview: {
        _links: {
          type: {
            href: apiurl + "rest/type/review/review"
          }
        },
        title: {
          value: null
        },
        rating: {
          value: null
        },
        body: {
          value: null
        },
        review_type:[{
          target_id: null,
          target_type: "taxonomy_term",
        }],
        status:[{
          target_id: null,
          target_type: "taxonomy_term",
        }],
        owner_id:[{
          target_id: null,
          target_type: "user",
        }],
        renter_id:[{
          target_id: null,
          target_type: "user",
        }],
        vehicle_id:[{
          target_id: null,
          target_type: "vehicle",
        }],
      },
      review_types: []
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

    axios.get(apiurl + "api/v1.0/review_types?_format=hal_json")
    .then(response => {
      this.review_types = response.data;
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

      let updateVehicle = {
        _links: {
          type: {
            href: apiurl + "rest/type/vehicle/vehicle"
          }
        },
        status:[{
          target_id: 11,
          target_type: "taxonomy_term",
        }],
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/vehicle/" + this.rental.vehicle_id + "?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.pass
        },
        data: updateVehicle
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.log(error.response.data.message);
      })
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

      let updateVehicle = {
        _links: {
          type: {
            href: apiurl + "rest/type/vehicle/vehicle"
          }
        },
        status:[{
          target_id: 10,
          target_type: "taxonomy_term",
        }],
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/vehicle/" + this.rental.vehicle_id + "?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.pass
        },
        data: updateVehicle
      })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.log(error.response.data.message);
      })
    },
    postReview() {
      this.resetReview();
      if (this.newReview.review_type[0].target_id == 7) {
        //owner review
        this.newReview.owner_id[0].target_id = this.rental.vehicle_owner_id
      } else if (this.newReview.review_type[0].target_id == 8) {
        //renter review
        this.newReview.renter_id[0].target_id = this.rental.renter_id
      } else if (this.newReview.review_type[0].target_id == 9) {
        //vehicle review
        this.newReview.vehicle_id[0].target_id = this.rental.vehicle_id
      }
      axios({
        method: 'post',
        url: apiurl + "entity/review?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.pass
        },
        data: this.newReview
      })
      .then( (response) => {
        console.log(response);
        location.reload();
      })
      .catch((error) => {
        console.log(error.response.status);
        console.log(error.response.headers);
        console.log(error.response.data.message);
      });
    },
    resetReview() {
      this.newReview.renter_id[0].target_id = null;
      this.newReview.owner_id[0].target_id = null;
      this.newReview.vehicle_id[0].target_id = null;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>

</style>
