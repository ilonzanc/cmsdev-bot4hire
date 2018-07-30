<template>
  <div id="update-profile">
      <div class="container">
      <header class="title-header">
        <h1>Update profile</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <form @submit.prevent="onSubmit">
        <!-- TODO: update avatar -->
        <label for="first_name">First Name *</label>
        <input type="text" id="first_name" name="first_name" placeholder="Your firstname..." v-bind:class="{ 'filled-in': currentUser.first_name }" v-model="currentUser.first_name">
        <p v-if="errors.name" class="message error">{{ errors.name }}</p>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Your username..." v-bind:class="{ 'filled-in': currentUser.last_name }" v-model="currentUser.last_name">
        <p v-if="errors.last_name" class="message error">{{ errors.last_name }}</p>
        <div class="checkbox-group">
          <input type="checkbox" id="display_name" v-model="currentUser.display_name">
          <label for="display_name">Display your full name</label>
        </div>
        <label for="bio">Bio</label>
        <textarea name="bio" id="bio" placeholder="Tell something about yourself..." v-model="currentUser.bio"></textarea>
        <p v-if="errors.bio" class="message error">{{ errors.bio }}</p>
        <label for="tel">Phone Number</label>
        <input type="text" id="tel" name="tel" placeholder="Your phone number..." v-bind:class="{ 'filled-in': currentUser.tel }" v-model="currentUser.tel">
        <p v-if="errors.tel" class="message error">{{ errors.tel }}</p>
        <div class="checkbox-group">
          <input type="checkbox" id="display_tel" v-model="currentUser.display_tel">
          <label for="display_tel">Display your phone number</label>
        </div>
        <label for="comm_frequency">Comm. frequency</label>
        <input type="text" id="comm_frequency" name="comm_frequency" placeholder="Your comm. frequency..." v-bind:class="{ 'filled-in': currentUser.comm_frequency }" v-model="currentUser.comm_frequency">
        <p v-if="errors.comm_frequency" class="message error">{{ errors.comm_frequency }}</p>
        <div class="checkbox-group">
          <input type="checkbox" id="display_comm_frequency" v-model="currentUser.display_comm_frequency">
          <label for="display_comm_frequency">Display your comm. frequency</label>
        </div>
        <button type="submit" class="btn widebtn">Update profile</button>
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
      currentUser: { },
      errors: {
        name: null,
        last_name: null,
        bio: null,
      }
    }
  },
    mounted() {
      axios.get(apiurl + "api/v1.0/profiles/" + this.$parent.loggedInUser.current_user.uid + "?_format=hal_json")
      .then(response => {
      console.log(response)
      this.currentUser = response.data[0];

      this.currentUser.display_comm_frequency = parseInt(this.currentUser.display_comm_frequency);
      this.currentUser.display_name = parseInt(this.currentUser.display_name);
      this.currentUser.display_tel = parseInt(this.currentUser.display_tel);
    });
  },
  methods: {
    onSubmit() {
      let self = this;
      this.resetFields();
      let updatedProfile = {
        _links: {
          type: {
            href: apiurl + "rest/type/profile/profile"
          }
        },
        name: {
          value: this.currentUser.first_name
        },
        last_name: {
          value: this.currentUser.last_name
        },
        display_name: {
          value: this.currentUser.display_name
        },
        bio: {
          value: this.currentUser.bio
        },
        tel: {
          value: this.currentUser.tel
        },
        display_tel: {
          value: this.currentUser.display_tel
        },
        comm_frequency: {
          value: this.currentUser.comm_frequency
        },
        display_comm_frequency: {
          value: this.currentUser.display_comm_frequency
        }
      };

      axios({
        method: 'patch',
        url: apiurl + "admin/structure/profile/" + this.currentUser.id + "?_format=hal_json",
        headers: {
        'Accept': 'application/hal+json',
        'Content-Type': 'application/hal+json',
        'X-CSRF-Token': this.$parent.loggedInUser.csrf_token,
      },
      auth: {
        username: this.$parent.loggedInUser.current_user.name,
        password: this.$parent.loggedInUser.current_user.password
      },
      data: updatedProfile
      })
      .then((response) => {
        console.log(response);
        location.href = '/dashboard';
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
