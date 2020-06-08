const state = {
    course: null
}

const getters = {
    courseDetail(state){
        return state.course;
    }
}

const mutations = {
    setCourse(state, course){
        state.course = course
    }
}

const actions = {
    set(context, course){
        context.commit('setCourse', course);
    }
}

export default{
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}