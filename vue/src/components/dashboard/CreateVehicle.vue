<template>
  <div id="create-vehicle">
      <div class="container">
        <header class="title-header">
          <h1>New vehicle</h1>
          <svg version="1.1" id="title-line" x="0px" y="0px"
            viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
            <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
          </svg>
        </header>
        <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" placeholder="Name of your vehicle..." v-model="vehicle.name.value">
            <label for="body">Description</label>
            <textarea id="body" name="body" placeholder="Description of your vehicle..." v-model="vehicle.description.value"></textarea>
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" placeholder="Price of your vehicle per day..." v-model="vehicle.price.value">
          </div>
          <div class="column column-sm-12 column-6">
            <label for="seats">Seats *</label>
            <input type="text" id="seats" name="seats" placeholder="Number of seats in your vehicle..." v-model="vehicle.seats.value">
            <label for="age">Age</label>
            <input type="text" id="age" name="age" placeholder="Age of your vehicle..." v-model="vehicle.age.value">
            <label for="vehicle_type">Type of vehicle *</label>
            <select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type_id" >
              <option value="" selected>- Select a type -</option>
              <option v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id" :value="vehicle_type.id">{{vehicle_type.name}}</option>
            </select>
            <label for="locations">Pickup Location *</label>
            <select class="form-control" name="vehicle_type_id" v-model="vehicle.location_id" >
              <option selected value="">- Selecteer een plaats -</option>
              <option v-for="location in locations" v-bind:key="location.id" :value="location.id">{{location.name}}</option>
            </select>
            <router-link class="btn" to="/">Go to map</router-link>
            <label for="image">Vehicle image *</label>
            <input id="vehicle_image" type="file" @change="uploadImage()">
            <div class="image-border">
              <div class="uploaded-image" :style='"background: url( http://localhost:8888" + vehicle.image_url + ") no-repeat center; background-size: contain"'></div>
            </div>
            <button type="submit" class="btn widebtn">Voertuig toevoegen</button>
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
        vehicle_type_id:[{
          target_id :"",
          target_type: "vehicle_type",
        }],
        location_id:[{
          target_id :"",
          target_type: "location",
        }],
        image:[{
          target_id :"",
        }],
      },
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
  },
  methods: {
    onSubmit() {
      let self = this;
      axios({
          method: 'post',
          url: apiurl + "entity/vehicle?_format=hal_json",
          headers: {
            'Accept': 'application/hal+json',
            'Content-Type': 'application/hal+json',
            'X-CSRF-Token': self.user.csrf_token,
          },
          auth: {
            username: self.user.current_user.name,
            password: self.password
          },
          data: self.vehicle
        })
        .then((response) => {
          console.log(response);
          location.href = '/overzicht/voertuig/' + response.data.id[0].value;
        })
        .catch((error) => {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error', error.message);
          }
          console.log(error.config);
        });


    },
    uploadImage() {
      let self = this;
      let file = document.getElementById('vehicle_image').files[0];
      var reader = new FileReader();
      reader.onloadend = () => {
        this.uploadedImage.data[0].value = file;
        this.uploadedImage.filemime.value = file.type;
        this.uploadedImage.filename.value = file.name;

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
          self.vehicle.image[0].target_id = response.data.fid[0].value;

        })
        .catch((error) => {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error', error.message);
          }
          console.log(error.config);
        });
      }
      reader.readAsDataURL(file);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
