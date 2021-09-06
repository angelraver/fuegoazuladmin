import Api from '@/services/Api'

export default {
  fetch (params) {
    return Api().post('turno', params)
  },

  add (params) {
    return Api().post('turno', params)
  },

  update (params) {
    return Api().post('turno', params)
  }
}
