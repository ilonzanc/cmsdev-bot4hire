<template>
  <div id="update-vehicle">
      <div class="container">
      <h1>Voertuig bewerken</h1>
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" placeholder="Naam van je voertuig..." v-model="vehicle.name">
            <label for="body">Beschrijving</label>
            <textarea id="body" name="body" placeholder="Beschrijving van voertuig..." v-model="vehicle.description"></textarea>
            <label for="price">Prijs (Shanix/dag)</label>
            <input type="text" id="price" name="price" placeholder="Prijs van je voertuig..." v-model="vehicle.price">
          </div>
          <div class="column column-sm-12 column-6">
            <label for="seats">Zitplaatsen</label>
            <input type="text" id="seats" name="seats" placeholder="Zitplaatsen van je voertuig..." v-model="vehicle.seats">
            <label for="age">Leeftijd (in miljoen jaar)</label>
            <input type="text" id="age" name="age" placeholder="Leeftijd van je voertuig..." v-model="vehicle.age">
            <select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type_id" >
              <option value="">- Select a type -</option>
              <option :selected="vehicle_type.id" v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id" :value="vehicle_type.id">{{vehicle_type.name}}</option>
            </select>
            <label for="locations">Pickup Location *</label>
            <select class="form-control" name="vehicle_type_id" v-model="vehicle.location_id" >
              <option value="">- Selecteer een plaats -</option>
              <option :selected="location.id" v-for="location in locations" v-bind:key="location.id" :value="location.id">{{location.name}}</option>
            </select>
            <router-link class="btn" to="/">Go to map</router-link>
            <label for="image">Vehicle image *</label>
            <input id="vehicle_image" type="file" @change="encodeImageFileAsURL()">
            <div class="image-border">
              <div class="uploaded-image" :style='"background: url( http://localhost:8888" + vehicle.image_url + ") no-repeat center; background-size: contain"'></div>
            </div>
            <button type="submit" class="btn widebtn">Voertuig bewerken</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../../utilities/Form.js';

export default {
  name: 'update-vehicle',
  data () {
    return {
    user: this.$parent.user,
    password: this.$parent.user_password,
    vehicle: {},
    uploadedImage: {
      _links: {
        type: [{
          href: "http://localhost:8888/rest/type/file/image"
        }]
      },
      filename: {
        value: "input.png"
      },
      filemime: {
        value: "input.png"
      },
      data: [{
        value: "randomdata"
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

    axios.get(apiurl + 'api/v1.0/vehicles/' + this.$route.params.id + '?_format=hal_json')
    .then(response => {
      console.log(response)
      this.vehicle = response.data[0];
    })
    .catch(error => {
      console.log(error);
    });
  },
  methods: {
    onSubmit() {
      let self = this;

      let updateVehicle = {
        _links: {
          type: {
            href: apiurl + "rest/type/vehicle/vehicle"
          }
        },
        name: {
          value: self.vehicle.name
        },
        description: {
          value: self.vehicle.description
        },
        price: {
          value: self.vehicle.price
        },
        seats: {
          value: self.vehicle.seats
        },
        age: {
          value: self.vehicle.age
        },
        image: {
          value: self.vehicle.image
        },
        vehicle_type_id: [{
          target_id: self.vehicle.vehicle_type_id,
          target_type: "",
        }],
        location_id: [{
          target_id: self.vehicle.location_id,
          target_type: "",
        }],
        image: [{
          target_id: self.vehicle.image_id,
        }],
      };

      axios({
        method: 'post',
        url: "http://localhost:8888/file/upload/vehicle/vehicle/image?_format=hal_json",
        headers: {
          'Content-Type': 'application/octet-stream',
          'X-CSRF-Token': self.user.csrf_token,
          'Content-Disposition': 'file; filename="'+ self.uploadedImage.filename.value + '"'
        },
        auth: {
          username: self.user.current_user.name,
          password: self.password
        },
        data: self.uploadedImage.data[0].value
      })
      .then((response) => {
        console.log(response);
        updateVehicle.image[0].target_id = response.data.fid[0].value;
        axios({
          method: 'patch',
          url: apiurl + "admin/structure/vehicle/" + this.$route.params.id + "?_format=hal_json",
          headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': self.user.csrf_token,
        },
        auth: {
          username: self.user.current_user.name,
          password: self.password
        },
        data: updateVehicle
        })
        .then((response) => {
          console.log(response);
          //location.href = '/dashboard/vehicles/' + response.data.id[0].value;
        })
        .catch(function(error) {
          console.log(error);
        });
      })
      .catch((error) => {
        console.log(error);
      });
    },
    encodeImageFileAsURL() {
      let file = document.getElementById('vehicle_image').files[0];
      var reader = new FileReader();
      reader.onloadend = () => {
        let commaPos = reader.result.indexOf(',');
        let imageData = reader.result.substr(commaPos + 1, reader.result.length);
        this.uploadedImage.data[0].value = file;
        this.uploadedImage.filemime.value = file.type;
        this.uploadedImage.filename.value = file.name;
      }
      reader.readAsDataURL(file);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
