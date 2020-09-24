import Vue from 'vue'
import Router from 'vue-router'
import Top from './components/Top'
import TimeTable from './components/TimeTable'
import Register from './components/Register'
import Login from './components/Login'
import Detail from './components/Detail'
import Setting from './components/Setting'
import SettingColor from './components/Course-color'
import SettingPeriod from './components/Period-setting'
import PasswordReset from './components/Reset'
import MyPage from './components/MyPage'
import Calendar from './components/Calendar'
import CalendarDetail from './components/Calendar-detail'
import Semester from './components/Semester'

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
        },
        {
            path: '/setting/color',
            name: 'setting-color',
            component: SettingColor,
        },
        {
            path: '/setting/period',
            name: 'setting-period',
            component: SettingPeriod
        },
        {
            path: '/setting/user/password-reset',
            name: 'password-reset',
            component: PasswordReset
        },
        {
            path: '/setting/mypage',
            name: 'mypage',
            component: MyPage
        },
        {
            path: '/setting/mypage/semester',
            name: 'semester',
            component: Semester
        }
    ]
})