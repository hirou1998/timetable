import Vue from 'vue'
import Vuex from 'vuex'

import course from './course'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    course
  }
})

export default store