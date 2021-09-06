import Vue from 'vue'
import Router from 'vue-router'
import Landing from '@/components/Landing'
import TurnosLanding from '@/components/Turno/TurnosLanding'
import Calendar from '@/components/components/Calendar'
import Clientes from '@/components/Cliente/Clientes'
import ClienteDetails from '@/components/Cliente/ClienteDetails'
import ClienteForm from '@/components/Cliente/ClienteForm'
import TurnoForm from '@/components/Turno/TurnoForm'
import Tratamientos from '@/components/Tratamiento/Tratamientos'
import TratamientoForm from '@/components/Tratamiento/TratamientoForm'
import Historial from '@/components/Historial'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Landing,
      name: 'Landing'
    },
    {
      path: '/turnos',
      component: TurnosLanding,
      name: 'TurnosLanding'
    },
    {
      path: '/canlendar',
      component: Calendar,
      name: 'Calendar'
    },
    {
      path: '/clientes',
      component: Clientes,
      name: 'Clientes'
    },
    {
      path: '/cliente/:id',
      component: ClienteDetails,
      name: 'ClienteDetails'
    },
    {
      path: '/clienteform/:id',
      component: ClienteForm,
      name: 'ClienteForm'
    },
    {
      path: '/turnoform/:idCliente',
      component: TurnoForm,
      name: 'TurnoForm'
    },
    {
      path: '/tratamientos',
      component: Tratamientos,
      name: 'Tratamientos'
    },
    {
      path: '/tratamientoform/:id',
      component: TratamientoForm,
      name: 'TratamientoForm'
    },
    {
      path: '/historial',
      component: Historial,
      name: 'Historial'
    }
  ]
})
