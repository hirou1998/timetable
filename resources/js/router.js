import Vue from 'vue'
import Router from 'vue-router'
import Top from './components/Top'
import TimeTable from './components/TimeTable'
import Register from './components/Register'
import Login from './components/Login'
import Detail from './components/Detail'
import Setting from './components/Setting'
import Calendar from './components/Calendar'
import CalendarDetail from './components/Calendar-detail'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'top',
            component: Top 
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/timetable',
            name: 'timetable',
            component: TimeTable
        },
        {
            path: '/timetable/detail',
            name: 'detail',
            component: Detail
        },
        {
            path: '/setting',
            name: 'setting',
            component: Setting
        },
        {
            path: '/calendar',
            name: 'calendar',
            component: Calendar
        },
        {
            path: '/calendar/detail',
            name: 'calendar-detail',
            component: CalendarDetail,
            props: true
        }
    ]
})