<template>
  <div class="neiwu">
      <h1>Nieuw voertuig</h1>
        <form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal_json" @submit.prevent="onSubmit">
          <label for="title">Naam</label>
          <input type="text" id="title" name="title" placeholder="Naam van je voertuig" v-model="vehicle.name.value">
          <label for="body">Beschrijving</label>
          <!-- <textarea id="body" name="body" placeholder="Beschrijving van voertuig" v-model="form.body"></textarea>
          <select class="form-control" name="party_id" v-model="form.tid" >
              <option value="">- Selecteer een type -</option>
              <option v-for="vehicle_type in vehicle_types" :value="vehicle_type.tid">{{vehicle_type.name}}</option>
          </select> -->
          <button type="submit" class="btn widebtn">Voertuig toevoegen</button>
        </form>
  </div>
</template>

<script>
import axios from 'axios';
import Form from '../utilities/Form.js';

export default {
  name: 'neiwu',
  data () {
    return {
		user: this.$parent.user,
      	vehicle: {
			_links: {
				type: {
					href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/vehicle/vehicle"
				}
			},
			name: {
				value: "Nieuw voertuig 2"
			}
      },                 
      vehicle_types: {},
    }
  },
  mounted() {
    axios.get('http://localhost/cmsdev-bot4hire/drupal/api/vehicle_types')
      .then(response => {
        console.log(response)
        this.vehicle_types = response.data;
        });
  },
  methods: {
    onSubmit() {
      var self = this;
      axios({
        method: 'post',
        url: "http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal_json",
        headers: {
		  	"Authorization": "Basic YWRtaW46c2VjcmV0",
        },
        data: self.vehicle
      }).then(function (response) {
        console.log(response);
      }).catch(function(error) {
        console.log(error);
      });
      /* axios.post('http://localhost/cmsdev-bot4hire/drupal/entity/vehicle?_format=hal+json', {
        name: this.vehicle.name.value,
      }) */
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>

<!--
{
  "_links": {
    "type": {
      "href": "http://localhost/cmsdev-bot4hire/drupal/rest/type/node/vehicles"
    }
  },
  "title": {
    "value": "Teeessttt"
  },
  "field_prijs": {
	"value": 1
  },
  "field_beschikbaar": {
	"value": false
  },
  "type": {
    "target_id": "voertuig"
  }
}
-->
