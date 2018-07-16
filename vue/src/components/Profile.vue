<template>
  <div id="profile">
      <div class="container">
      <section class="section__profile">
        <header class="title-header">
          <h1>{{user.username}}</h1>
          <svg version="1.1" id="title-line" x="0px" y="0px"
            viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
            <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
          </svg>
        </header>
        <div class="image-border">
          <div class="profile-image" :style='"background: url( http://localhost:8888/sites/default/files" + user.uri + ") no-repeat center; background-size: contain"'></div>
        </div>
        <h2>{{user.first_name + " " + user.last_name}}</h2>
        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
        <p>{{user.bio}}</p>
      </section>
      <section class="section-contact-information">
        <h2>Contact Information</h2>
        <dl>
          <div class="dr">
            <dt><i class="fa fa-envelope-o"></i></dt>
            <dd>johndoe@mail.com</dd>
          </div>
          <div class="dr">
            <dt><i class="fa fa-phone"></i></dt>
            <dd>+32498055032</dd>
          </div>
          <div class="dr">
            <dt><i title="Comm. frequency" class="fa fa-rss"></i></dt>
            <dd>152.63.25</dd>
          </div>
        </dl>
      </section>
      <section class="section__vehicles-list">
        <h2>Available vehicles</h2>
        <div class="row">
          <div class="column column-sm-12 column-4" v-bind:key="vehicle.id" v-for="vehicle in vehicles">
            <section class="section__vehicle">
              <router-link :to='"/overzicht/voertuig/" + vehicle.id'>
                <div class="row">
                  <div class="column column-sm-3 column-12">
                    <div class="vehicle__image" :style='"background: url(" + vehicle.image + ") no-repeat center; background-size: contain"'></div>
                    </div>
                  <div class="column column-sm-9 column-12">
                    <i class="fa fa-angle-right"></i>
                    <h2>{{vehicle.name}}</h2>
                    <p>{{vehicle.vehicle_type}}</p>
                  </div>
                </div>
              </router-link>
            </section>
          </div>
        </div>
      </section>
      <section class="review-list">
        <h2>Reviews</h2>
        <article v-for="review in reviews" v-bind:key="review.id" class="review">
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
        </article>
        <p v-if="reviews.length == 0">This user doesn't have any reviews yet.</p>
      </section>
      </div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
    name: 'profile',
    data () {
    return {
      user: {},
      vehicles: [],
      rentals: [],
      reviews: []
    }
  },
  mounted () {
    console.log('Profile Component Mounted');
    let self = this;
    axios({
      method: 'get',
      url: apiurl + "api/v1.0/profiles/" + this.$route.params.id + "?_format=hal_json",
      headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': self.user.csrf_token,
      },
      /* auth: {
        username: self.user.current_user.name,
        password: self.user.current_user.pass,
      }, */
    })
    .then(response => {
      console.log(response)
      this.user = response.data[0];
      if (this.user.uri) {
        let slashPos = this.user.uri.indexOf("/");
        this.user.uri = this.user.uri.substring(slashPos + 1, this.user.uri.length);
      }
      this.getVehiclesByUser();
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
      axios.get(apiurl + '/api/v1.0/users/' + this.$route.params.id + '/vehicles?_format=hal_json')
      .then(response => {
        console.log(response)
        this.vehicles = response.data[0];
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
