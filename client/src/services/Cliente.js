import Api from '@/services/Api'

export default {
  fetch () {
    return Api().get('cliente')
  },

  add (params) {
    return Api().post('cliente', params)
  },

  update (params) {
    return Api().post('cliente/' + params.id, params)
  },

  get (id) {
    return Api().get('cliente/' + id)
  },

  delete (id) {
    return Api().post('cliente/' + id, { delete: true })
  }
}
