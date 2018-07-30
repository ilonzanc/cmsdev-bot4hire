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
            <input type="text" id="name" name="name" placeholder="Name of your vehicle..." v-bind:class="{ 'filled-in': vehicle.name.value }" v-model="vehicle.name.value">
            <p v-if="errors.name" class="message error">{{ errors.name }}</p>
            <label for="body">Description</label>
            <textarea id="body" name="body" placeholder="Description of your vehicle..." v-bind:class="{ 'filled-in': vehicle.description.value }" v-model="vehicle.description.value"></textarea>
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" placeholder="Price of your vehicle per day..." v-bind:class="{ 'filled-in': vehicle.price.value }" v-model="vehicle.price.value">
            <p v-if="errors.price" class="message error">{{ errors.price }}</p>
          </div>
          <div class="column column-sm-12 column-6">
            <label for="seats">Seats *</label>
            <input type="text" id="seats" name="seats" placeholder="Number of seats in your vehicle..." v-bind:class="{ 'filled-in': vehicle.seats.value }" v-model="vehicle.seats.value">
            <p v-if="errors.seats" class="message error">{{ errors.seats }}</p>
            <label for="age">Age</label>
            <input type="text" id="age" name="age" placeholder="Age of your vehicle..." v-bind:class="{ 'filled-in': vehicle.age.value }" v-model="vehicle.age.value">
            <label for="vehicle_type">Type of vehicle *</label>
            <select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type_id[0].target_id" >
              <option value="" selected disabled>- Select a type -</option>
              <option v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id" :value="vehicle_type.id">{{vehicle_type.name}}</option>
            </select>
            <p v-if="errors.vehicle_type_id" class="message error">{{ errors.vehicle_type_id }}</p>
            <label for="locations">Pickup Location *</label>
            <select class="form-control" name="vehicle_type_id" v-model="vehicle.location_id[0].target_id" >
              <option selected value="" disabled>- Select a location -</option>
              <option v-for="location in locations" v-bind:key="location.id" :value="location.id">{{location.name}}</option>
            </select>
            <p v-if="errors.location_id" class="message error">{{ errors.location_id }}</p>
            <!-- TODO: Add cybertron map -->
            <!-- <router-link class="btn" to="/">Go to map</router-link> -->
            <label for="image">Vehicle image *</label>
            <div class="upload-btn-wrapper">
              <!-- TODO: icon upload animation -->
              <button class="btn upload-btn">Upload a file</button>
              <input id="vehicle_image" type="file" @change="uploadImage()">
            </div>
            <p v-if="errors.image" class="message error">{{ errors.image }}</p>
            <div v-if="vehicle_image_url != null" class="image-border">
              <div  class="uploaded-image" :style='"background: url( http://localhost:8888" + vehicle_image_url + ")"'></div>
            </div>
            <h2>Specifications</h2>
            <label for="power" class="range-label">Power</label>
            <div class="range-box">
              {{ vehicle.power.value }}
            </div>
            <input type="range" min="0" max="5" step="1" v-model="vehicle.power.value" class="slider" id="myRange" list="steplist">
            <p v-if="errors.power" class="message error">{{ errors.power }}</p>
            <label for="speed" class="range-label">Speed</label>
            <div class="range-box">
              {{ vehicle.speed.value }}
            </div>
            <input type="range" min="0" max="5" v-model="vehicle.speed.value" class="slider" id="myRange">
            <p v-if="errors.speed" class="message error">{{ errors.speed }}</p>
            <label for="accuracy" class="range-label">Accuracy</label>
            <div class="range-box">
              {{ vehicle.accuracy.value }}
            </div>
            <input type="range" min="0" max="5" v-model="vehicle.accuracy.value" class="slider" id="myRange">
            <p v-if="errors.accuracy" class="message error">{{ errors.accuracy }}</p>
            <button type="submit" class="btn widebtn">add new vehicle</button>
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
          value: null
        },
        description: {
          value: null
        },
        price: {
          value: null
        },
        seats: {
          value: null
        },
        age: {
          value: null
        },
        vehicle_type_id:[{
          target_id: null,
          target_type: "vehicle_type",
        }],
        location_id:[{
          target_id: null,
          target_type: "location",
        }],
        image:[{
          target_id: null,
        }],
        status:[{
          target_id: 10,
          target_type: "taxonomy_term",
        }],
        power: {
          value: 0
        },
        speed: {
          value: 0
        },
        accuracy: {
          value: 0
        }
      },
      uploadedImage: {
        _links: {
          type: [{
            href: "http://localhost:8888/rest/type/file/image"
          }]
        },
        filename: {
          value: null
        },
        filemime: {
          value: null
        },
        data: [{
          value: null
        }]
      },
      vehicle_image_url: null,
      vehicle_types: {},
      locations: {},
      errors: {
        name: null,
        price: null,
        seats: null,
        vehicle_type_id: null,
        location_id: null,
        image: null,
        power: null,
        speed: null,
        accuracy: null
      },
    }
  },
  computed: {
    computedWidth(){
      return this.width;
    },
  },
  mounted() {
    axios.get(apiurl + "api/v1.0/vehicle_types?_format=hal_json")
    .then(response => {
      this.vehicle_types = response.data;
    });

    axios.get(apiurl + 'api/v1.0/locations?_format=hal_json')
    .then(response => {
      this.locations = response.data;
    });
  },
  methods: {
    onSubmit() {
      let self = this;
      this.resetFields();
      axios({
        method: 'post',
        url: apiurl + "entity/vehicle?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
        },
        auth: {
          username: this.$parent.loggedInUser.current_user.name,
          password: this.$parent.loggedInUser.current_user.pass
        },
        data: this.vehicle
      })
      .then((response) => {
        console.log(response);
        location.href = '/overview/vehicle/' + response.data.id[0].value;
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.status);
          console.log(error.response.headers);
          console.log(error.response.data.message);

          let errorstring = error.response.data.message;
          errorstring = errorstring.replace(new RegExp('This value should not be null.', 'g'), 'This field is required!');
          let returnPos = errorstring.indexOf('\n');
          errorstring = errorstring.substr(returnPos + 1, errorstring.length);
          let errorarray = this.loopThroughErrors(errorstring);
          this.setErrors(errorarray);
        } else if (error.request) {
          console.log(error.request);
        } else {
          console.log('Error', error.message);
        }
        console.log(error.config);
      });
    },
    loopThroughErrors(errorstring) {
      let array = [];
      let loopstring = errorstring;
      while (loopstring.length > 0) {
        let currentReturnPos = loopstring.indexOf('\n');
        let error = loopstring.substr(0, currentReturnPos);
        array.push(error);
        loopstring = loopstring.substr(currentReturnPos + 1, loopstring.length);
      }
      return array;
    },
    setErrors(errors) {
      let self = this;
      for (let error of errors) {
        let colonPos = error.indexOf(':');
        let errorField = error.substr(0, colonPos);
        self.errors[errorField] = error.substr(colonPos + 1, error.length);
      }
    },
    resetFields () {
      let self = this;
      for (let key in self.errors) {
        if (self.errors.hasOwnProperty(key)) {
            self.errors[key] = null;
        }
      }
    },
    uploadImage () {
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
            'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
            'Content-Disposition': 'file; filename="'+ self.uploadedImage.filename.value + '"'
          },
          auth: {
            username: this.$parent.loggedInUser.current_user.name,
            password: this.$parent.loggedInUser.current_user.pass
          },
          data: this.uploadedImage.data[0].value
        })
        .then((response) => {
          console.log(response);
          this.vehicle_image_url = response.data.uri[0].url;
          this.vehicle.image[0].target_id = response.data.fid[0].value;

        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.data.message);
            console.log(error.response.status);
            console.log(error.response.headers);
            let errors = [];

          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.log(error.config);
        });
      }
      reader.readAsDataURL(file);
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
