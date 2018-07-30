<template>
  <div id="dashboard">
    <div class="container">
      <header class="title-header">
        <h1>Dashboard</h1>
        <svg version="1.1" id="title-line" x="0px" y="0px"
          viewBox="0 0 250 29" style="enable-background:new 0 0 250 29;" xml:space="preserve">
          <polyline style="fill:none;stroke:#67B1FC;stroke-miterlimit:10;" points="250,25 40,25 25,9.9 0,9.9 "/>
        </svg>
      </header>
      <section class="actions">
        <h2>What do you want to do?</h2>
        <router-link to="/dashboard/vehicles/new" class="btn">Add a new vehicle</router-link>
        <router-link to="/dashboard/vehicles" class="btn">Manage your vehicles</router-link>
        <router-link to="/dashboard/profile/edit" class="btn">Edit your profile</router-link>
        <router-link to="/dashboard/rentalcontracts" class="btn">Manage your rental contracts<i v-if="gotRentalNotifications == true" class="fa fa-envelope"></i></router-link>
      </section>
      <section class="statistics">
        <!-- TODO: Add graphs -->
        <h2>Statistics</h2>
        <section class="stat-top">
          <article class="stat-numbers">
            <h2>{{ availableCount }}</h2>
            <h3>Current available vehicles</h3>
          </article>
          <article class="stat-numbers">
            <h2>{{ unavailableCount }}</h2>
            <h3>Current rented out vehicles</h3></article>
          <article class="stat-numbers">
            <h2>{{ popularVehicle }}</h2>
            <h3>Most popular vehicle</h3>
          </article>
        </section>
        <article class="stat-graph">
          <h3>Rented vehicles this year</h3>
          <div class="chart"></div>
        </article>
      </section>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import * as d3 from 'd3';

export default {
    name: 'dashboard',
    data () {
    return {
      vehicles: [],
      rentals: [],
      gotRentalNotifications: false,
      popularVehicle: null,
      availableCount: 0,
      unavailableCount: 0,
      lineData: [{
        x: "2017-12-01",
        y: 0
      }, {
        x: "2018-01-01",
        y: 0
      }, {
        x: "2018-02-01",
        y: 0
      }, {
        x: "2018-03-01",
        y: 0
      }, {
        x: "2018-04-01",
        y: 0
      }, {
        x: "2018-05-01",
        y: 0
      }, {
        x: "2018-06-01",
        y: 0
      }, {
        x: "2018-07-01",
        y: 0
      }, {
        x: "2018-08-01",
        y: 0
      }, {
        x: "2018-09-01",
        y: 0
      }, {
        x: "2018-10-01",
        y: 0
      }, {
        x: "2018-11-01",
        y: 0
      }, {
        x: "2018-12-01",
        y: 0
      }],
      //data: [99, 71, 78, 25, 36, 92],
      line: '',
    }
  },
  mounted () {
    console.log('Dashboard Component Mounted');

    axios.get(apiurl + 'api/v1.0/rentals/owner/' + this.$parent.loggedInUser.current_user.uid + '?_format=hal_json')
    .then(response => {
      this.rentals = response.data;

      let rentalsThisYear = [];

      for (let rental of this.rentals) {
        rental.accepted_by_owner = parseInt(rental.accepted_by_owner);
        rental.returned_by_renter = parseInt(rental.returned_by_renter);
        rental.return_confirmed_by_owner = parseInt(rental.return_confirmed_by_owner);

        if (rental.accepted_by_owner == 1) {
          let rental_date = new Date(rental.start_date);
          let rentalMonth = rental_date.getMonth();
          this.lineData[rentalMonth].y++;
        }

        if (rental.accepted_by_owner == 0) {
          this.gotRentalNotifications = true;
        } else if (rental.returned_by_renter == 1 && rental.return_confirmed_by_owner == 0) {
          this.gotRentalNotifications = true;
        }
      }

      this.countAvailableVehicles();
      this.countUnavailableVehicles();
      this.getPopularVehicle();
      this.drawLine();
    })
    .catch(error => {
      console.log(error);
    });

  },
  methods: {
    countAvailableVehicles(){
      axios({
        method: "get",
        url: apiurl + "api/v1.0/users/" + this.$parent.loggedInUser.current_user.uid + "/vehicles?_format=hal_json",
        headers: {  }
      })
      .then((response) => {
        this.vehicles = response.data;
        for (let vehicle of this.vehicles) {
          if (vehicle.status == "available") {
            this.availableCount++;
          }
        }
      })
      .catch((error) => {
        console.log(error);
      });
    },
    countUnavailableVehicles(){
      axios({
        method: "get",
        url: apiurl + "api/v1.0/users/" + this.$parent.loggedInUser.current_user.uid + "/vehicles?_format=hal_json",
        headers: {  }
      })
      .then((response) => {
        this.vehicles = response.data;
        for (let vehicle of this.vehicles) {
          if (vehicle.status == "unavailable") {
            this.unavailableCount++;
          }
        }
      })
      .catch((error) => {
        console.log(error);
      });
    },
    getPopularVehicle(){
      let vehicleArray = [];
      for (let rental of this.rentals) {
        vehicleArray.push(rental.vehicle_name);
      }
      let counts = {};
      vehicleArray.forEach( x => { counts[x] = (counts[x] || 0)+1; });
      this.popularVehicle = Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b);
    },
    drawLine() {
      // set the dimensions and margins of the graph
      var margin = {top: 32, right: 32, bottom: 32, left: 32},
          width = 750 - margin.left - margin.right,
          height = 350 - margin.top - margin.bottom;

      var formatMonth = d3.timeFormat("%b");

      // set the ranges
      //var x = d3.scaleLinear().range([0, width]);
      var x = d3.scaleTime().range([0, width]);
      var y = d3.scaleLinear().range([height, 0]);

      // define the line
      var valueline = d3.line()
          .x(function(d) { return x(d.x); })
          .y(function(d) { return y(d.y); });

      // append the svg obgect to the body of the page
      // appends a 'group' element to 'svg'
      // moves the 'group' element to the top left margin
      var svg = d3.select(".chart").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .append("g")
          .attr("transform",
                "translate(" + margin.left + "," + margin.top + ")");

      this.lineData.forEach((d) => {
        let date = new Date(d.x);
        d.x = date;
        d.y = +d.y;
      });

      // Scale the range of the data
      //x.domain(d3.extent(this.lineData, function(d) { return d.x; }));
      x.domain(d3.extent(this.lineData, (d) => { return d.x; }));
      y.domain([0, d3.max(this.lineData, (d) => { return d.y; })]);

      svg.append("linearGradient")
        .attr("id", "line-gradient")
        .attr("gradientUnits", "userSpaceOnUse")
        .attr("x1", "0%")
        .attr("y1", "0%")
        .attr("x2", "0%")
        .attr("y2", "100%")
        .selectAll("stop")
        .data([
            {offset: "0%", color: "#67b1fc", opacity: "0.5"},
            {offset: "80%", color: "#67b1fc", opacity: "0"},
        ])
        .enter()
        .append("stop")
        .attr("offset", function(d) { return d.offset; })
        .attr("stop-opacity", function(d) { return d.opacity; })
        .attr("stop-color", function(d) { return d.color; });

      var maxX = x(d3.extent(this.lineData, function(d) { return d.x; })[1]);

      // Add the valueline path/background
      svg.append("path")
        //.data([this.lineData])
        .attr("class", "bg")
        .attr("d", '' + valueline(this.lineData) + 'L' + maxX + "," + y(0) + "L0," + y(0) )
        .attr("stroke", "none")
        .attr("fill","url(#line-gradient)");

      // Add the valueline path.
      svg.append("path")
        .data([this.lineData])
        .attr("class", "line")
        .attr("d", valueline)
        .attr("stroke", "#67b1fc")
        .attr("fill", "none");

      // Add the X Axis
      svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .attr("class", "x-axis")
        .call(d3.axisBottom(x).tickFormat(d3.timeFormat("%b")));

      let highestYValue = d3.max(this.lineData, (d) => { return d.y; });
      let tickCount = highestYValue / 2;

      // Add the Y Axis
      svg.append("g")
        .call(d3.axisLeft(y).ticks(tickCount))
        .attr("class", "y-axis");

      svg.selectAll("text").
        attr("fill", "white");
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
  dt,
  dd {
    float: left;
  }

  dt {
    clear: left;
  }

  path {
    stroke: steelblue;
    stroke-width: 2;
    fill: none;
  }

  .axis path,
  .axis line {
    fill: none;
    stroke: grey;
    stroke-width: 1;
    shape-rendering: crispEdges;
  }

  .line {
    color: #67b1fc;
  }

  .bg {
    fill: url(#line-gradient);
    stroke-width: 2px;
  }

  .tick text {
    fill: #67b1fc;
  }
</style>
