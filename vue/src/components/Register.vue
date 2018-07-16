<template>
  <div id="register">
      <div class="container">
      <header class="title-header">
        <h1>Register new user</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <form @submit.prevent="onSubmit">
        <label for="user">Avatar</label>
        <input id="user_avatar" type="file" @change="uploadImage()">
        <p v-if="errors.image" class="message error">{{ errors.image }}</p>
        <div v-if="avatar" class="image-border">
          <div  class="uploaded-image" :style='"background: url( http://localhost:8888" + avatar + ") no-repeat center; background-size: contain"'></div>
        </div>
        <label for="name">Username</label>
        <input type="text" id="name" name="name" placeholder="Your username..." required v-bind:class="{ 'filled-in': user.name.value }" v-model="user.name.value">
        <p v-if="errors.name" class="message error">{{ errors.name }}</p>
        <label for="mail">Email Address</label>
        <input type="email" id="mail" name="mail" placeholder="Your emailaddress..." required v-bind:class="{ 'filled-in': user.mail.value }" v-model="user.mail.value">
        <p v-if="errors.mail" class="message error">{{ errors.mail }}</p>
        <label for="pass">Password</label>
        <input type="password" id="pass" name="pass" placeholder="********" required v-bind:class="{ 'filled-in': user.pass.value }" v-model="user.pass.value">
        <button type="submit" class="btn widebtn">Register</button>
      </form>
      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'register',
  data () {
    return {
      user: {
        _links: {
          type: {
            href: apiurl + "rest/type/user/user"
          }
        },
        name: { value: null },
        mail: { value: null },
        pass: { value: null },
      },
      profile: {
        _links: {
          type: {
            href: apiurl + "rest/type/profile/profile"
          }
        },
        name: { value: null },
        user_id:[{
          target_id: null,
          target_type: "user",
        }],
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
      avatar: {

      },
      errors: {
        name: null,
        mail: null
      }
    }
  },
  mounted () {
    console.log('Register Component Mounted');
  },
  methods: {
    onSubmit() {
      let self = this;
      this.resetFields();
      let file = document.getElementById('user_avatar').files[0];
      var reader = new FileReader();
      //this.user.user_picture.value = file;
      axios({
        method: 'post',
        url: apiurl + "user/register?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          //"X-CSRF-Token": "ECWys4TAdc0FAUXf2zIbO8DSpwUh5kjl7YMgv-0qZO0",
        },
        data: self.user
      })
      .then((response) => {
        console.log(response.data);
        /* localStorage.setItem('loggedInUser', JSON.stringify(response.data));
        location.href = '/profiel/' + response.data.current_user.uid; */
        this.profile.name.value = response.data.name[0].value;
        this.profile.user_id[0].target_id = response.data.uid[0].value;
        self.loginUser();
      })
      .catch((error) => {
        if (error.response) {
          console.log(error.response.data.message);
          console.log(error.response.status);
          console.log(error.response.headers);

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
    loginUser() {
      let registeredUser = {
        name: this.user.name.value,
        pass: this.user.pass.value
      }
      axios({
        method: 'post',
        url: apiurl + "user/login?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          "X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
        },
        data: registeredUser
      })
      .then((response) => {
        console.log(response.data);
        response.data.current_user.pass = registeredUser.pass;
        localStorage.setItem('loggedInUser', JSON.stringify(response.data));
        this.createProfile();
      })
      .catch((error) => {
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
    createProfile() {
      axios({
        method: 'post',
        url: apiurl + "entity/profile?_format=hal_json",
        headers: {
          'Accept': 'application/hal+json',
          'Content-Type': 'application/hal+json',
          "X-CSRF-Token": "T48cuYVRu1CRiXoV7-O35YUNV5A_j7Ro9jT5z5St0OA",
        },
        data: this.profile
      })
      .then((response) => {
        console.log(response.data);
        location.href = '/profiel/' + response.data.current_user.uid;
      })
      .catch((error) => {
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
    uploadImage () {
      let self = this;
      let file = document.getElementById('user_avatar').files[0];
      var reader = new FileReader();
      reader.onloadend = () => {
        this.uploadedImage.data[0].value = file;
        this.uploadedImage.filemime.value = file.type;
        this.uploadedImage.filename.value = file.name;

        axios({
          method: 'post',
          url: "http://localhost:8888/file/upload/user/user/user_picture?_format=hal_json",
          headers: {
            'Content-Type': 'application/octet-stream',
            //'X-CSRF-Token': self.user.csrf_token,
            'Content-Disposition': 'file; filename="'+ self.uploadedImage.filename.value + '"'
          },
          auth: {
            username: "admin",
            password: "secret"
          },
          data: self.uploadedImage.data[0].value
        })
        .then((response) => {
          console.log(response);
          self.avatar = response.data;
          self.user.user_picture.value = response.data.fid[0].value;
        })
        .catch((error) => {
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
      }
      reader.readAsDataURL(file);
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
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
