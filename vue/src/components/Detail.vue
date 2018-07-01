<template>
  <div id="detail">
      <div class="container">
      <router-link class="breadcrumbs" to="/overzicht"><i class="fa fa-chevron-left"></i> terug naar overzicht</router-link>
      <div class="row">
        <div class="column column-sm-12 column-6">
          <img :src="vehicle.image">
        </div>
        <div class="column column-sm-12 column-6">
          <h1>{{vehicle.name}}</h1>
          <p class="vehicle__type">{{vehicle.vehicle_type}}</p>
          <p>{{vehicle.description}}</p>
          <p>Eigenaar: <router-link :to="'/profiel/' + vehicle.uid">{{vehicle.user_id}}</router-link></p>
          <p>Zitplaatsen: {{vehicle.seats}}</p>
          <p>Prijs: {{vehicle.price}} Shanix/dag</p>
          <p>Leeftijd: {{vehicle.age}} miljoen jaar</p>
          <p>Locatie: {{vehicle.pickup_location}}</p>
          <router-link v-if="activeuser.current_user.uid !== vehicle.uid" :to="vehicle.id + '/huren'" class="btn">Huren</router-link>
          <router-link v-if="activeuser.current_user.uid == vehicle.uid" :to="'/voertuig/' + vehicle.id + '/bewerken'" class="btn smallbtn"><i class="fa fa-pencil"></i> edit</router-link>
        </div>
      </div>
      <h2>Reviews</h2>
      <section class="section__review-list">
        <section v-for="review in reviews" v-bind:key="review.id" class="section__review">
          <div class="row">
            <div class="column column-sm-4 column-2">
              <router-link :to="'/profiel/' + review.user_id">
                <i class="fa fa-user-circle"></i>
                <p>{{review.user_username}}</p>
              </router-link>
            </div>
            <div class="column column-sm-8 column-10">
              <h3>{{review.title}}</h3>
              <star-rating :value="review.rating" :disabled="true"></star-rating>
              <p>{{review.body}}</p>
            </div>
          </div>
        </section>
        <p v-if="reviews.length == 0">Dit voertuig heeft nog geen reviews.</p>
      </section>
      <section v-if="activeuser.current_user.uid !== vehicle.uid" class="section__newreview">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <h2>Nieuw review toevoegen</h2>
            <form @submit.prevent="onSubmit">
              <label for="title">Titel</label>
              <input type="text" id="title" name="title" placeholder="Titel van je review..." required v-model="newReview.title.value">
              <label for="rating">Rating</label>
              <input type="text" name="rating" placeholder="Rating op 5..." required v-model="newReview.rating.value">
              <label for="body">Beschrijving</label>
              <textarea id="body" name="body" placeholder="Jouw review..." required v-model="newReview.body.value"></textarea>
              <button type="submit" class="btn widebtn">Review toevoegen</button>
            </form>
          </div>
        </div>
      </section>
      </div>
  </div>
</template>

<script>
import axios from "axios";
import StarRating from './tags/StarRating';

export default {
  name: 'detail',
  components: { StarRating },
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
      console.log(response.data[0])
      this.reviews = response.data;
    })
    .catch(error => {
      console.log(error);
    });
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: apiurl + "entity/review?_format=hal_json",
        headers: {
          //'X-CSRF-Token': self.user.csrf_token,
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': self.activeuser.csrf_token,
        },
        auth: {
          username: self.activeuser.current_user.name,
          password: self.password
        },
        data: self.newReview
      }).then(function (response) {
        console.log(response);
        location.reload();
      }).catch(function(error) {
        console.log(error);
      });
    },
    displayStarRating(count) {
      let starArray;

      let star = "<h1>Hello</h1>";
      return star;
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    .specbar {
        border: 1px solid black;
        display: block;
        height: 30px;
    }

    .specbarsize-1 {
        width: 30px;
    }

    .specbarsize-2 {
        width: 60px;
    }

    .specbarsize-3 {
        width: 90px;
    }

    .specbarsize-4 {
        width: 120px;
    }

    .specbarsize-5 {
        width: 150px;
    }
</style>
