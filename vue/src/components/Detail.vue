<template>
  <div id="vehicle-detail">
      <div class="container">
      <router-link class="breadcrumbs" to="/overzicht"><i class="fa fa-chevron-left"></i> back to overview</router-link>
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
                <div class="vehicle__image" :style='"background: url( http://localhost:8888" + vehicle.image_url + ") no-repeat center; background-size: contain"'></div>
              </div>
              <svg version="1.1" id="hologram" x="0px" y="0px"
                viewBox="0 0 836 395" style="enable-background:new 0 0 836 395;" xml:space="preserve">
                <ellipse style="fill:#67B1FC;" cx="415.6" cy="350.5" rx="10.5" ry="3.2"/>
                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="415.5931" y1="353.6997" x2="415.5931" y2="10.447">
                  <stop  offset="0" style="stop-color:#67B1FC"/>
                  <stop  offset="2.264887e-02" style="stop-color:#62A7EE;stop-opacity:0.9774"/>
                  <stop  offset="0.1038" style="stop-color:#5087C3;stop-opacity:0.8962"/>
                  <stop  offset="0.1912" style="stop-color:#406B9C;stop-opacity:0.8088"/>
                  <stop  offset="0.2837" style="stop-color:#33537B;stop-opacity:0.7163"/>
                  <stop  offset="0.3828" style="stop-color:#284061;stop-opacity:0.6172"/>
                  <stop  offset="0.491" style="stop-color:#20314C;stop-opacity:0.509"/>
                  <stop  offset="0.6128" style="stop-color:#1A263E;stop-opacity:0.3872"/>
                  <stop  offset="0.7598" style="stop-color:#172036;stop-opacity:0.2402"/>
                  <stop  offset="1" style="stop-color:#161E33;stop-opacity:0"/>
                </linearGradient>
                <polygon style="opacity:0.3;fill:url(#SVGID_1_);" points="412.2,353.7 12,10.4 819.2,10.4 	"/>
                <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="415.5931" y1="353.6997" x2="415.5931" y2="10.447">
                  <stop  offset="0" style="stop-color:#67B1FC"/>
                  <stop  offset="2.264887e-02" style="stop-color:#62A7EE;stop-opacity:0.9774"/>
                  <stop  offset="0.1038" style="stop-color:#5087C3;stop-opacity:0.8962"/>
                  <stop  offset="0.1912" style="stop-color:#406B9C;stop-opacity:0.8088"/>
                  <stop  offset="0.2837" style="stop-color:#33537B;stop-opacity:0.7163"/>
                  <stop  offset="0.3828" style="stop-color:#284061;stop-opacity:0.6172"/>
                  <stop  offset="0.491" style="stop-color:#20314C;stop-opacity:0.509"/>
                  <stop  offset="0.6128" style="stop-color:#1A263E;stop-opacity:0.3872"/>
                  <stop  offset="0.7598" style="stop-color:#172036;stop-opacity:0.2402"/>
                  <stop  offset="1" style="stop-color:#161E33;stop-opacity:0"/>
                </linearGradient>
                <polygon style="opacity:0.3;fill:url(#SVGID_2_);" points="413.8,353.7 200.2,10.4 631,10.4 	"/>
                <ellipse style="fill:none;stroke:#67B1FC;stroke-width:3;stroke-miterlimit:10;" cx="415.6" cy="356.1" rx="94" ry="28.5"/>
                <ellipse style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" cx="415.6" cy="350.5" rx="18.3" ry="5.5"/>
              </svg>
            </section>
            <div class="row important-details">
              <div class="column column-sm-6 column-2">
                <!-- <img class="icon small-icon" src="../assets/images/marker.svg"> -->
                <i class="fa fa-map-marker"></i>
                {{vehicle.location_name}}
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
            <p>Owner: <router-link :to="'/profiel/' + vehicle.user_id">{{vehicle.user_name}}</router-link></p>
            <p>Seats: {{vehicle.seats}}</p>
            <p>Age: {{vehicle.age}} million year(s)</p>
            <tabs>
              <tab name="specs" :selected="true">
                <table>
                  <tr>
                    <td>power</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>speed</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>accuracy</td>
                    <td>
                      <div class="spec-bar power-bar">
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
                        <div class="spec-block"></div>
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
            <router-link v-if="this.$parent.loggedInUser.current_user.uid !== vehicle.uid" :to="vehicle.id + '/huren'" class="btn">Rent this vehicle</router-link>
            <router-link v-if="this.$parent.loggedInUser.current_user.uid == vehicle.uid" :to="'/voertuig/' + vehicle.id + '/bewerken'" class="btn smallbtn"><i class="fa fa-pencil"></i> edit</router-link>
          </div>
        </div>
      </section>
      <h2>Reviews</h2>
      <section class="review-list">
        <section v-for="review in reviews" v-bind:key="review.id" class="section__review">
          <div class="row">
            <div class="column column-sm-3 column-2">
              <router-link :to="'/profiel/' + review.user_id">
                <i class="fa fa-user-circle"></i>

              </router-link>
            </div>
            <div class="column column-sm-9 column-10">
              <h3>{{review.user_name}}</h3>
              <!-- <h3>{{review.title}}</h3> -->
              <section class="vehicle-rating">
                <i v-bind:key="n" v-for="n in review.rating" class="fa fa-star" style="marginRight: 0.5rem; color: #c9ed8b"></i>
              </section>

              <p>{{review.body}}</p>
            </div>
          </div>
        </section>
        <p v-if="reviews.length == 0">This vehicle doesn't have any reviews yet.</p>
      </section>
      <section v-if="this.$parent.loggedInUser.current_user.uid !== vehicle.uid" class="section__newreview">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <h2><i class="fa fa-plus"></i> Add a new review</h2>
            <form @submit.prevent="onSubmit">
              <label for="title">Title</label>
              <input type="text" id="title" name="title" placeholder="Title of your review..." required ref="title" v-bind:class="{ 'filled-in': newReview.title.value }" v-model="newReview.title.value" @focus.prevent="onFocus()">
              <label for="rating">Rating</label>
              <input type="text" name="rating" placeholder="Rating on 5..." required v-model="newReview.rating.value">
              <label for="body">Description</label>
              <textarea id="body" name="body" placeholder="Tell us more..." required v-model="newReview.body.value"></textarea>
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
import Tabs from './Tabs.vue';
import Tab from './Tab.vue';

export default {
  name: 'detail',
  components:{
    'tabs': Tabs,
    'tab': Tab
  },
  data () {
    return {
      activeuser: this.$parent.user,
      password: this.$parent.user_password,
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
      }
    }
  },
  mounted () {
    console.log('Detail Component Mounted');
    this.makeTitleSmaller();
    axios.get(apiurl + 'api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
    .then(response => {
      this.vehicle = response.data[0];
      this.newReview.vehicle_id[0].target_id = response.data[0].id;
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
    onFocus() {
      this.$refs.title.classList.toggle('hey');
    },
    makeTitleSmaller() {
      let title = document.querySelector('.title-header h1');
      console.log(title.clientWidth);
      if (title.clientWidth > 280) {
        //title.style.fontSize = "1.4rem";
      }
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">

</style>
