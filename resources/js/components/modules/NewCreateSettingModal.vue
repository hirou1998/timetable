<template>
    <section class="modal-base modal-popup">
        <div class="modal-base-inner create-setting-modal">
            <p class="create-setting-head">ユーザー登録ありがとうございます。<br>まずは初期設定をしてください。</p>
            <div 
                class="create-setting-section create-setting-sectzion-first" 
                :data-position="current === 0 ? 'center' : 'left'"
            >
                <p>1. 授業時間</p>
                <ul class="setting-list-container">
                <template v-for="(period, index) in setting.periods">
                    <period-setting-item 
                        class="setting-list" 
                        :key="index" 
                        :period="period" 
                        :num="index" 
                        is-editing=true 
                        v-model="setting.periods[index]" 
                    />
                </template>
                </ul>
            </div>
            <div
                class="create-setting-section create-setting-section-second"
                :data-position="current === 1 ? 'center' : current === 0 ? 'right' : 'left'"
            >
                <p>2. 学期登録</p>
                <semester-form v-model="defaultSemester" :types="types" />
            </div>
            <div
                class="create-setting-section create-setting-section-second"
                :data-position="current === 2 ? 'center' : 'right'"
            >
                <p>3. 大学/入学・卒業年登録</p>
                <setting-other-form v-model="settingValue" />
            </div>
            <button class="create-setting-prev" @click="movePrev" v-if="current > 0">&lt;&lt;前へ</button>
            <button class="create-setting-next" @click="moveNext" v-if="current < 2">>>次へ</button>
            <button class="create-setting-next create-setting-confirm btn btn-sm btn-success" @click="register" v-if="current == 2">登録</button>
        </div>
    </section>
</template>

<script>
import PeriodSettingItem from './Period-setting-item'
import SemesterForm from './SemesterForm'
import SettingOtherForm from './SettingOtherForm'

export default {
    components: {
        PeriodSettingItem,
        SemesterForm,
        SettingOtherForm
    },
    props: ['setting'],
    data: function(){
        return{
            current: 0,
            defaultSemester: {
                endDate: {
                    year: '',
                    month: '',
                    day: ''
                },
                startDate: {
                    year: '',
                    month: '',
                    day: ''
                },
                start_date: null,
                end_date: null,
                type: '',
                year: '',
                user_id: ''
            },
            types: [],
            settingValue: {
                university: null,
                enter_year: null,
                graduation_year: null
            }
        }
    },
    computed: {
        isSemesterFilled(){
            if(this.defaultSemester.year && this.defaultSemester.type && this.defaultSemester.startDate.year && this.defaultSemester.startDate.month && this.defaultSemester.startDate.day && this.defaultSemester.endDate.year && this.defaultSemester.endDate.month && this.defaultSemester.endDate.day){
                return true
            }else{
                return false
            }
        }
    },
    methods: {
        formatDate(year, month, day){
            return year + '-' + month + '-' + day;
        },
        formatSemester(){
            let editedStartDate = this.defaultSemester.startDate;
            let editedEndDate = this.defaultSemester.endDate;
            this.defaultSemester.start_date = this.formatDate(editedStartDate.year, editedStartDate.month, editedStartDate.day);
            this.defaultSemester.end_date = this.formatDate(editedEndDate.year, editedEndDate.month, editedEndDate.day);
        },
        getSemesterTypes(){
            axios.get('/api/setting/semester/types')
            .then(({data}) => {
                this.types = data;
            })
            .catch(err => {
                console.log(err)
            })
        },
        moveNext(){
            // if(this.current === 1){
            //     if(!this.isSemesterFilled){
            //         alert('入力していない項目があります')
            //         return
            //     }
            // }
            this.current++;
        },
        movePrev(){
            this.current--;
        },
        register(){
            this.formatSemester();
            let inputValue = {
                periods: {...this.setting.periods},
                semester: {
                    year: this.defaultSemester.year,
                    type: this.defaultSemester.type,
                    start_date: this.defaultSemester.start_date,
                    end_date: this.defaultSemester.end_date
                },
                university: this.settingValue.university,
                enter_year: this.settingValue.enter_year,
                graduation_year: this.settingValue.graduation_year,
            }
            this.$emit('register', inputValue);
        }
    },
    created(){
        this.getSemesterTypes();
    }
}
</script>