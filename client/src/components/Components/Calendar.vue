<template>
  <div id="calendar">
    <div class="month">
      <ul>
        <li id="prev" @click="previous">&#10094;</li>
        <li>{{monthLabel}}</li>
        <li>{{yearLabel}}</li>
        <li id="next" @click="next">&#10095;</li>
      </ul>
    </div>

    <ul id="weekdays">  
      <li>Domingo</li>
      <li>Lunes</li>
      <li>Martes</li>
      <li>Miércoles</li>
      <li>Jueves</li>
      <li>Viernes</li>
      <li>Sábado</li>
    </ul>

    <ul id="days">
      <li v-for="(item, index) in daysBlocks" v-bind:key="index" v-bind:class="item.blockClass">
        {{ item.day }}
      </li>
    </ul>

  </div>
</template>

<script>
import { MdButton, MdContent, MdTabs } from 'vue-material/dist/components'

let today = new Date()
let dayInt = today.getDate()
let month = today.getMonth()
let year = today.getFullYear()
let months = [
  'Enero',
  'Febrero',
  'Marzo',
  'Abril',
  'Mayo',
  'Junio',
  'Julio',
  'Agosto',
  'Septiembre',
  'Octubre',
  'Noviembre',
  'Diciembre'
]

export default {
  name: 'calendar',
  components: { MdButton, MdContent, MdTabs },
  data () {
    return {
      daysBlocks: [],
      monthLabel: '',
      yearLabel: ''
    }
  },
  mounted () {
    this.showCalendar(month, year)
  },
  methods: {
    showCalendar (month, year) {
      let firstDay = new Date(year, month).getDay()
      this.blankDates(firstDay)
      let totalDays = this.daysInMonth(month, year)
      for (let day = 1; day <= totalDays; day++) {
        let blockClass = 'inactive'
        if (
          dayInt === day &&
          month === today.getMonth() &&
          year === today.getFullYear()
        ) {
          blockClass = 'active'
        }
        this.daysBlocks.push({ day, blockClass })
      }
      this.monthLabel = months[month]
      this.yearLabel = year
    },
    daysInMonth (month, year) {
      return new Date(year, month + 1, 0).getDate()
    },
    blankDates (count) {
      this.daysBlocks = []
      for (let x = 0; x < count; x++) {
        this.daysBlocks.push({ day: '' })
      }
    },
    next () {
      year = month === 11 ? year + 1 : year
      month = (month + 1) % 12
      this.showCalendar(month, year)
    },
    previous () {
      year = month === 0 ? year - 1 : year
      month = month === 0 ? 11 : month - 1
      this.showCalendar(month, year)
    }
  }
}

</script>

<style>
* {
	box-sizing: border-box;
}
ul {
	list-style-type: none;
}
body {
	font-family: Verdana, sans-serif;
}

#calendar {
	max-width: 600px;
}

.month {
	padding: 1em 1em;
	width: 100%;
	background: #1abc9c;
	text-align: center;
}

.month ul {
	margin: 0;
	padding: 0;
	display: grid;
	grid-template-columns: 1fr 1fr 1fr 1fr;
	grid-template-rows: 1fr;
}

.month ul li {
	color: white;
	font-size: 20px;
	text-transform: uppercase;
	letter-spacing: 3px;
}

#weekdays {
	margin: 0;
	padding: 0;
	background-color: #ddd;
}

#weekdays li {
	color: #666;
	text-align: center;
}

#days {
	padding: 0 0 10px 0;
	background: #eee;
	margin: 0;
}

#days li {
	list-style-type: none;
	text-align: center;
	font-size: 16px;
	color: #000;
	padding: 15px;
  margin: 1px;
}

#days li.inactive {
  background: #ccc;
}

#days li.active {
	background: #1abc9c;
	color: white !important;
}

#next,
#prev {
	cursor: pointer;
}

/* Add media queries for smaller screens */
@media screen and (max-width: 720px) {
	.weekdays li,
	#days li {
		width: 13.1%;
	}
}

@media screen and (max-width: 420px) {
	.weekdays li,
	#days li {
		width: 12.5%;
	}
	#days li .active {
		padding: 2px;
	}
}

@media screen and (max-width: 290px) {
	.weekdays li,
	#days li {
		width: 12.2%;
	}
}

#days,
#weekdays {
	display: grid;
	grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
	grid-template-rows: 1fr;
}

@media all and (-ms-high-contrast: none) {
	#days {
		display: -ms-grid;
		-ms-grid-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
		-ms-grid-rows: 1fr 1fr 1fr 1fr 1fr 1fr;
	}
}

</style>