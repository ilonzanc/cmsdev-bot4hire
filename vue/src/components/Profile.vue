<template>
  <div id="profile">
      <div class="container">
      <section class="section__profile">
        <header class="title-header">
          <h1>{{user.name}}</h1>
          <svg version="1.1" id="title-line" x="0px" y="0px"
            viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
            <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
          </svg>
        </header>
        <div class="image-border">
          <div class="profile-image" :style='"background: url( http://localhost:8888/sites/default/files" + user.uri + ") no-repeat center; background-size: contain"'></div>
        </div>
      </section>
      <section class="section__vehicles-list">
        <h2>Voertuigen</h2>
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
        <router-link v-if="activeuser.current_user.uid == user.uid[0].value" class="btn" to="/voertuig/nieuw">Nieuw voertuig toevoegen</router-link>
      </section>
      </div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
    name: 'detail',
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
