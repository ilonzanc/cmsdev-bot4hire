<template>
  <div id="vehicle-detail">
      <div class="container">
      <router-link class="breadcrumbs" to="/overview"><i class="fa fa-chevron-left"></i> back to overview</router-link>
      <section class="section__vehicle">
        <header class="title-header">
          <h1>{{vehicle.name}}</h1>
          <svg version="1.1" id="title-line" x="0px" y="0px"
            viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
            <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
          </svg>
        </header>
        <div class="row">
          <div class="column column-sm-12 column-6">
            <section class="vehicle-hologram">
              <div class="image-border">
                <div class="vehicle__image" :style='"background-image: url( http://localhost:8888" + vehicle.image_url + ")"'></div>
              </div>
            </section>
            <div class="row important-details">
              <div class="column column-sm-6 column-2">
                <i class="vehicle-location">{{vehicle.location_name}}</i>
              </div>
              <div class="column column-sm-6 column-10">
                <section class="vehicle-price">
                    <span class="vehicle-price-number">{{vehicle.price}}</span>
                    <span class="vehicle-price-suffix">shanix</span>
                  </section>
              </div>
            </div>
          </div>
          <div class="column column-sm-12 column-6">
            <p class="vehicle__type">{{vehicle.vehicle_type}}</p>
            <p>{{vehicle.description}}</p>
            <p>Owner: <router-link :to="'/profile/' + vehicle.user_id">{{vehicle.user_name}}</router-link></p>
            <p>Seats: {{vehicle.seats}}</p>
            <p>Age: {{vehicle.age}} million year(s)</p>
            <tabs>
              <tab name="specs" :selected="true">
                <table>
                  <tr>
                    <td>power</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div v-if="vehicle.power > 0" v-bind:key="n" v-for="n in vehicle.power" class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>speed</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div v-if="vehicle.speed > 0" v-bind:key="n" v-for="n in vehicle.speed" class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>accuracy</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div v-if="vehicle.accuracy > 0" v-bind:key="n" v-for="n in vehicle.accuracy" class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                </table>
              </tab>
              <tab name="status">
                <table>
                  <tr>
                    <td>fuel levels</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                </table>
              </tab>
            </tabs>
            <router-link v-if="isLoggedIn == true && isOwner == false" :to="vehicle.id + '/rent'" class="btn">Rent this vehicle</router-link>
          </div>
        </div>
      </section>
      <div class="row">
        <div class="column column-sm-12 column-6">
          <h2>Reviews</h2>
          <section class="review-list">
            <article v-for="review in reviews" v-bind:key="review.id" class="review">
              <div class="image-border">
                <router-link class="profile-avatar" :to="'/profile/' + review.user_id">
                  <i class="fa fa-user-o"></i>
                </router-link>
              </div>
              <i class="review-created">{{review.created}}</i>
              <h3>{{review.user_name}}</h3>
              <h3>{{review.title}}</h3>
              <section class="vehicle-rating">
                <i v-bind:key="n" v-for="n in review.rating" class="fa fa-star" style="marginRight: 0.5rem; color: #c9ed8b"></i><i v-if="review.rating < 5" v-bind:key="n" v-for="n in (5 - review.rating)" class="fa fa-star-o" style="marginRight: 0.5rem; color: #c9ed8b"></i>
              </section>
              <p>{{review.body}}</p>
            </article>
            <p v-if="reviews.length == 0">This vehicle doesn't have any reviews yet.</p>
          </section>
        </div>
        <div class="column column-sm-12 column-6"></div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Tabs from '../components/Tabs.vue';
import Tab from '../components/Tab.vue';

export default {
  name: 'detail',
  components:{
    'tabs': Tabs,
    'tab': Tab
  },
  data () {
    return {
      vehicle: {},
      reviews: [],
      newReview: {
        _links: {
          type: {
            href: apiurl + "rest/type/review/review"
          }
        },
        title: {
          value: ''
        },
        body: {
          value: ''
        },
        rating: {
          value: ''
        },
        vehicle_id:[{
          target_id: "",
          target_type: "vehicle",
        }]
      },
      isLoggedIn: null,
      isOwner: null
    }
  },
  mounted () {
    console.log('Detail Component Mounted');
    axios.get(apiurl + 'api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
    .then(response => {
      this.vehicle = response.data[0];
      this.vehicle.power = parseInt(this.vehicle.power);
      if (isNaN(this.vehicle.power)) {
        this.vehicle.power = 0;
      }
      this.vehicle.speed = parseInt(this.vehicle.speed);
      if (isNaN(this.vehicle.speed)) {
        this.vehicle.speed = 0;
      }
      this.vehicle.accuracy = parseInt(this.vehicle.accuracy);
      if (isNaN(this.vehicle.accuracy)) {
        this.vehicle.accuracy = 0;
      }
      this.newReview.vehicle_id[0].target_id = response.data[0].id;

      if (this.$parent.loggedInUser) {
        this.isLoggedIn = true;
        console.log("User is logged in");
        if (this.$parent.loggedInUser.current_user.uid !== this.vehicle.user_id) {
          this.isOwner = false;
          console.log("This isn't the owner");
        } else {
          this.isOwner = true;
          console.log("This is the owner");
        }
      } else {
        this.isLoggedIn = false;
        console.log("User isn't logged in");
      }
    })
    .catch(error => {
      console.log(error);
    });

    axios.get(apiurl + 'api/v1.0/vehicle/' + this.$route.params.id + '/reviews?_format=hal_json')
    .then(response => {
      this.reviews = response.data;
      for(let review of this.reviews) {
        review.rating = parseInt(review.rating);
      }
    })
    .catch(error => {
      console.log(error);
    });
  },
  methods: {
    onSubmit() {
      axios({
        method: 'post',
        url: apiurl + "entity/review?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.name,
          password: this.$parent.loggedInUser.pass
        },
        data: this.newReview
      }).then( (response) => {
        console.log(response);
        location.reload();
      }).catch((error) => {
        console.log(error.response.status);
        console.log(error.response.headers);
        console.log(error.response.data.message);
      });
    },
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  .column {
    overflow: visible;
  }
</style>
