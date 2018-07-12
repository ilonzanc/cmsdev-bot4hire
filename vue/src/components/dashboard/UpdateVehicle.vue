<template>
  <div id="update-vehicle">
      <div class="container">
      <header class="title-header">
        <h1>Edit vehicle</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 89" style="enable-background:new 0 0 250 89;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,85 40,85 25,69.9 0,69.9 "/>
        </svg>
      </header>
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-6">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" placeholder="Naam van je voertuig..." v-model="vehicle.name">
            <p v-if="errors.name" class="message error">{{ errors.name }}</p>
            <label for="body">Beschrijving</label>
            <textarea id="body" name="body" placeholder="Beschrijving van voertuig..." v-model="vehicle.description"></textarea>
            <label for="price">Prijs (Shanix/dag)</label>
            <input type="text" id="price" name="price" placeholder="Prijs van je voertuig..." v-model="vehicle.price">
            <p v-if="errors.price" class="message error">{{ errors.price }}</p>
          </div>
          <div class="column column-sm-12 column-6">
            <label for="seats">Zitplaatsen</label>
            <input type="text" id="seats" name="seats" placeholder="Zitplaatsen van je voertuig..." v-model="vehicle.seats">
            <p v-if="errors.seats" class="message error">{{ errors.seats }}</p>
            <label for="age">Leeftijd (in miljoen jaar)</label>
            <input type="text" id="age" name="age" placeholder="Leeftijd van je voertuig..." v-model="vehicle.age">
            <select class="form-control" name="vehicle_type" v-model="vehicle.vehicle_type_id" >
              <option value="">- Select a type -</option>
              <option :selected="vehicle_type.id" v-for="vehicle_type in vehicle_types" v-bind:key="vehicle_type.id" :value="vehicle_type.id">{{vehicle_type.name}}</option>
            </select>
            <p v-if="errors.vehicle_type_id" class="message error">{{ errors.vehicle_type_id }}</p>
            <label for="locations">Pickup Location *</label>
            <select class="form-control" name="vehicle_type_id" v-model="vehicle.location_id" >
              <option value="">- Selecteer een plaats -</option>
              <option :selected="location.id" v-for="location in locations" v-bind:key="location.id" :value="location.id">{{location.name}}</option>
            </select>
            <p v-if="errors.location_id" class="message error">{{ errors.location_id }}</p>
            <router-link class="btn" to="/">Go to map</router-link>
            <label for="image">Vehicle image *</label>
            <input id="vehicle_image" type="file" @change="uploadImage()">
            <p v-if="errors.image[0].target_id" class="message error">{{ errors.image[0].target_id }}</p>
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
      errors: {
        name: null,
        price: null,
        seats: null,
        vehicle_type_id: null,
        location_id: null,
        image: [{
          target_id: null
        }]
      }
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
      //this.resetFields();
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
        location.href = '/dashboard/vehicles/' + response.data.id[0].value;
      })
      .catch((error) => {
        if (error.response) {
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

        let arrayNumber = error.indexOf('.0.');
        if (arrayNumber > 0) {
          let firstProp = errorField.substr(0, arrayNumber);
          let secondProp = errorField.substr(arrayNumber + 3, errorField.length);
          self.errors[firstProp][0][secondProp] = error.substr(colonPos + 2, error.length);
        } else {
          self.errors[errorField] = error.substr(colonPos + 1, error.length);
        }

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
          self.vehicle.image_id = response.data.fid[0].value;
          self.vehicle.image_url = response.data.uri[0].url;
        })
        .catch((error) => {
          console.log(error);
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
