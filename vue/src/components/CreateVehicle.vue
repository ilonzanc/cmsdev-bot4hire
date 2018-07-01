<template>
  <div id="create-vehicle">
      <div class="container">
        <h1>Nieuw voertuig</h1>
        <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" placeholder="Naam van je voertuig..." v-model="vehicle.name.value">
            <label for="body">Beschrijving</label>
            <textarea id="body" name="body" placeholder="Beschrijving van voertuig..." v-model="vehicle.description.value"></textarea>
            <label for="price">Prijs (Shanix/dag)</label>
            <input type="text" id="price" name="price" placeholder="Prijs van je voertuig..." v-model="vehicle.price.value">
          </div>
          <div class="column column-sm-12 column-6">
            <label for="seats">Zitplaatsen</label>
            <input type="text" id="seats" name="seats" placeholder="Zitplaatsen van je voertuig..." v-model="vehicle.seats.value">
            <label for="age">Leeftijd (in miljoen jaar)</label>
            <input type="text" id="age" name="age" placeholder="Leeftijd van je voertuig..." v-model="vehicle.age.value">
            <label for="vehicle_type">Type</label>
            <select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type_id[0].target_id" >
            <option value="" selected>- Selecteer een type -</option>
              <option v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id" :value="vehicle_type.id">{{vehicle_type.name}}</option>
            </select>
            <label for="locations">Ophaallocatie</label>
            <select class="form-control" name="vehicle_type_id" v-model="vehicle.location_id[0].target_id" >
              <option selected value="">- Selecteer een plaats -</option>
              <option v-for="location in locations" v-bind:key="location.id" :value="location.id">{{location.name}}</option>
            </select>
            <label for="image">Afbeelding</label>
            <input type="text" id="image" name="image" placeholder="Link naar afbeelding..." v-model="vehicle.image.value">
            <button type="submit" class="btn widebtn">Voertuig toevoegen</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../utilities/Form.js';

export default {
  name: 'create-vehicle',
  data () {
    return {
    user: this.$parent.user,
    password: this.$parent.user_password,
    vehicle: {
      _links: {
        type: {
          href: apiurl + "rest/type/vehicle/vehicle"
        }
      },
      name: {
        value: ""
      },
      description: {
        value: ""
      },
      price: {
        value: ""
      },
      seats: {
        value: ""
      },
      age: {
        value: ""
      },
      image: {
        value: ""
      },
      vehicle_type_id:[{
        target_id :"",
        target_type: "vehicle_type",
      }],
      location_id:[{
        target_id :"",
        target_type: "location",
      }]
    },
    vehicle_types: {},
    locations: {},
    }
  },
    mounted() {
      axios.get(apiurl + "api/v1.0/vehicle_types?_format=hal_json")
      .then(response => {
      console.log(response)
      this.vehicle_types = response.data;
    });

    axios.get(apiurl + 'api/v1.0/locations?_format=hal_json')
      .then(response => {
      console.log(response)
      this.locations = response.data;
    });
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: apiurl + "entity/vehicle?_format=hal_json",
        headers: {
      //'X-CSRF-Token': self.user.csrf_token,
      'Accept': 'application/hal+json',
      'Content-Type': 'application/hal+json',
      'X-CSRF-Token': self.user.csrf_token,
    },
    auth: {
      username: self.user.current_user.name,
      password: self.password
    },
        data: self.vehicle
      }).then(function (response) {
    console.log(response);
    location.href = '/overzicht/voertuig/' + response.data.id[0].value;
      }).catch(function(error) {
        console.log(error);
      });
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
