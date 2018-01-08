<template>
  <div class="neiwu">
      <h1>Nieuw voertuig</h1>
        <form method="POST" action="http://localhost/cmsdev-bot4hire/drupal/node?_format=hal_json" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
          <label for="title">Naam</label>
          <input type="text" id="title" name="title" placeholder="Naam van je voertuig" v-model="form.title.value">
          <label for="body">Beschrijving</label>
          <textarea id="body" name="body" placeholder="Beschrijving van voertuig" v-model="form.body"></textarea>
          <select class="form-control" name="party_id" v-model="form.tid" >
              <option value="">- Selecteer een type -</option>
              <option v-for="vehicle_type in vehicle_types" :value="vehicle_type.tid">{{vehicle_type.name}}</option>
          </select>
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
      form: new Form ({
          _links: {
            type: {
              href: "http://localhost/cmsdev-bot4hire/drupal/rest/type/node/vehicles"
            }
          },
          title: [{
            value: ''
          }],
          type: [{
            target_id: 'voertuig'
          }],
          body: '',
          tid: ''
        
      }),
      vehicle_types: {}
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
      this.form.submit('post', 'http://localhost/cmsdev-bot4hire/drupal/node?_format=hal_json').then(function(response){
          alert('Nieuw voertuig aangemaakt');
      });
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
