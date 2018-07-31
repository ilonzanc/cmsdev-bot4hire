<template>
  <div id="profile">
      <div class="container">
      <section v-if="!loaded">
        <loader></loader>
      </section>
      <div v-if="loaded">
        <section class="section__profile" >
          <header class="title-header">
            <h1>{{user.username}}</h1>
            <svg version="1.1" id="title-line" x="0px" y="0px"
              viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
              <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
            </svg>
          </header>
          <div class="image-border">
            <div class="profile-avatar">
              <i class="fa fa-user-o"></i>
            </div>
          </div>
          <h2>{{user.display_name == 1 ? user.first_name + " " + user.last_name : user.username}}</h2>
          <!-- TODO: calculate average review rating -->
          <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
          <p>{{user.bio}}</p>
        </section>
        <section class="section-contact-information">
          <h2>Contact Information</h2>
          <dl>
            <!-- TODO: if contact field isn't filled in, hide icons -->
            <div v-if="user.display_tel == 1 && user.tel.length > 1" class="dr">
              <dt><i class="fa fa-phone"></i></dt>
              <dd>{{user.tel}}</dd>
            </div>
            <div v-if="user.display_comm_frequency == 1 && user.comm_frequency.length > 1" class="dr">
              <dt><i title="Comm. frequency" class="fa fa-rss"></i></dt>
              <dd>{{user.comm_frequency}}</dd>
            </div>
          </dl>
        </section>
        <section class="section__vehicles-list">
          <h2>Available vehicles</h2>
          <div class="row">
            <!-- TODO: add missing vehicle details -->
            <div class="column column-sm-12 column-4" v-bind:key="vehicle.id" v-for="vehicle in vehicles">
              <section class="section__vehicle">
                <router-link :to='"/overview/vehicle/" + vehicle.id'>
                  <div class="image-border">
                    <div class="vehicle__image" :style='"background-image: url( http://localhost:8888" + vehicle.image_url + ")"'></div>
                  </div>
                  <i class="fa fa-angle-right"></i>
                  <h2>{{vehicle.name}}</h2>
                  <p>{{vehicle.vehicle_type}}</p>
                </router-link>
              </section>
            </div>
          </div>
        </section>
        <section class="review-list">
          <h2>Reviews</h2>
          <article v-for="review in reviewsAsRenter" v-bind:key="review.id" class="review">
            <div class="row">
              <div class="column column-sm-3 column-2">
                <router-link :to="'/profile/' + review.user_id">
                  <i class="fa fa-user-circle"></i>
                </router-link>
              </div>
              <div class="column column-sm-9 column-10">
                <!-- TODO: also add date of publication -->
                <h3>{{review.user_name}}</h3>
                <h3>{{review.title}}</h3>
                <section class="vehicle-rating">
                  <i v-bind:key="n" v-for="n in review.rating" class="fa fa-star" style="marginRight: 0.5rem; color: #c9ed8b"></i><i v-if="review.rating < 5" v-bind:key="n" v-for="n in (5 - review.rating)" class="fa fa-star-o" style="marginRight: 0.5rem; color: #c9ed8b"></i>
                </section>
                <p>{{review.body}}</p>
              </div>
            </div>
          </article>
          <article v-for="review in reviewsAsOwner" v-bind:key="review.id" class="review">
            <div class="row">
              <div class="column column-sm-3 column-2">
                <router-link :to="'/profile/' + review.user_id">
                  <i class="fa fa-user-circle"></i>

                </router-link>
              </div>
              <div class="column column-sm-9 column-10">
                <h3>{{review.user_name}}</h3>
                <h3>{{review.title}}</h3>
                <section class="vehicle-rating">
                  <i v-bind:key="n" v-for="n in review.rating" class="fa fa-star" style="marginRight: 0.5rem; color: #c9ed8b"></i><i v-if="review.rating < 5" v-bind:key="n" v-for="n in (5 - review.rating)" class="fa fa-star-o" style="marginRight: 0.5rem; color: #c9ed8b"></i>
                </section>
                <p>{{review.body}}</p>
              </div>
            </div>
          </article>
          <p v-if="reviewsAsRenter.length == 0 && reviewsAsOwner.length == 0">This user doesn't have any reviews yet.</p>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Loader from '../components/Loader.vue'

export default {
    name: 'profile',
    components: {
      'loader': Loader
    },
    data () {
    return {
      user: {},
      vehicles: [],
      rentals: [],
      reviewsAsRenter: [],
      reviewsAsOwner: [],
      averageRating: null,
      loaded: false
    }
  },
  mounted () {
    console.log('Profile Component Mounted');
    axios({
      method: 'get',
      url: apiurl + "api/v1.0/profiles/" + this.$route.params.id + "?_format=hal_json",
      headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
      },
    })
    .then(response => {
      console.log(response.data[0])
      this.loaded = true;
      this.user = response.data[0];
      if (this.user.uri) {
        let slashPos = this.user.uri.indexOf("/");
        this.user.uri = this.user.uri.substring(slashPos + 1, this.user.uri.length);
      }
      this.user.display_comm_frequency = parseInt(this.user.display_comm_frequency);
      this.user.display_email = parseInt(this.user.display_email);
      this.user.display_name = parseInt(this.user.display_name);
      this.user.display_tel = parseInt(this.user.display_tel);
      this.getVehiclesByUser();
      this.getReviewsByUser();
    })
    .catch(error => {
      if (error.response) {
        console.log(error.response.status);
        console.log(error.response.headers);
        console.log(error.response.data.message);

        let errors = [];

      } else if (error.request) {
        console.log(error.request);
      } else {
        console.log('Error', error.message);
      }
      console.log(error.config);
    });
  },
  methods: {
    getVehiclesByUser() {
      axios.get(apiurl + 'api/v1.0/users/' + this.user.user_id + '/vehicles?_format=hal_json')
      .then(response => {
        console.log(response.data);
        this.vehicles = response.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    getReviewsByUser() {
      axios.get(apiurl + 'api/v1.0/reviews/renter/' + this.user.user_id + '?_format=hal_json')
      .then(response => {
        console.log(response.data);
        this.reviewsAsRenter = response.data;
        for (let review of this.reviewsAsRenter) {
          review.rating = parseInt(review.rating);
        }
      })
      .catch(error => {
        console.log(error);
      });

      axios.get(apiurl + 'api/v1.0/reviews/owner/' + this.user.user_id + '?_format=hal_json')
      .then(response => {
        console.log(response.data);
        this.reviewsAsOwner = response.data;
        for (let review of this.reviewsAsOwner) {
          review.rating = parseInt(review.rating);
        }
      })
      .catch(error => {
        console.log(error);
      });
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
  .section__vehicles-list h2:first-child {
    clear: both;
  }
  .dr {
    margin-bottom: 1rem;
    overflow: auto;
  }
  dt, dd {
    float: left;

  }
  dd {
    padding: 1rem;
  }
  dt {
    clear: left;
    border-bottom: 2px solid #67b1fc;
    .fa {
      color: #67b1fc;
      padding: 1rem;
    }
  }
</style>
