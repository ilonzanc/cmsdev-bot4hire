<template>
  <div id="update-profile">
      <div class="container">
      <header class="title-header">
        <h1>Update profile</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 89" style="enable-background:new 0 0 250 89;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,85 40,85 25,69.9 0,69.9 "/>
        </svg>
      </header>
      <form @submit.prevent="onSubmit">
        <label for="username">Username *</label>
        <input type="text" id="username" name="username" placeholder="Your username..." v-bind:class="{ 'filled-in': user.name.value }" v-model="user.name.value">
        <p v-if="errors.name" class="message error">{{ errors.name }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../../utilities/Form.js';

export default {
  name: 'update-profile',
  data () {
    return {
      user: {
        name: {
          value: null
        }
      },
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
      axios.get(apiurl + "api/v1.0/users/" + this.user.current_user.uid + "?_format=hal_json")
      .then(response => {
      console.log(response)
      this.user = response.data[0];
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
