<template>
  <div class="overview">
      <h1>Overzicht</h1>
      <section v-for="vehicle in vehicles">
        <router-link :to='"/voertuig/" + vehicle.nid'>      
          <h2>{{vehicle.title}}</h2>
          <p>{{vehicle.term_node_tid}}</p>
        </router-link>
      </section>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'overview',
  data () {
    return {
      vehicles: []
    }
  },
  mounted () {
      axios.get('http://localhost/cmsdev-bot4hire/drupal/api/vehicles')
      .then(response => {
        console.log(response)
        this.vehicles = response.data;
        });
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
