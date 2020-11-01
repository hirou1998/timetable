<template>
    <div class="modal-base">
        <section class="semester-edit-window">
            <div class="semester-edit-inner">
                <form>
                    <div class="semester-edit-block">
                        <p class="setting-form-text">年度</p>
                        <select v-model="year" class="form-control">
                            <option :value="item" v-for="item in years" :key="item">{{item}}</option>
                        </select>
                    </div>
                    <div class="semester-edit-block">
                        <p class="setting-form-text">学期</p>
                        <select v-model="type" class="form-control">
                            <option :value="item" v-for="item in types" :key="item">{{item}}</option>
                        </select>
                    </div>
                    <div class="semester-edit-block">
                        <p class="setting-form-text">開始日</p>
                        <div class="d-flex">
                            <select v-model="startDateYear" class="form-control">
                                <option :value="item" v-for="item in years" :key="item">{{item}}</option>
                            </select>
                            <select v-model="startDateMonth" class="form-control">
                                <option :value="item" v-for="item in 12" :key="item">{{item}}</option>
                            </select>
                            <select v-model="startDateDay" class="form-control">
                                <option :value="item" v-for="item in days(startDateYear, startDateMonth)" :key="item">{{item}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="semester-edit-block">
                        <p class="setting-form-text">終了日</p>
                        <div class="d-flex">
                            <select v-model="endDateYear" class="form-control">
                                <option :value="item" v-for="item in years" :key="item">{{item}}</option>
                            </select>
                            <select v-model="endDateMonth" class="form-control">
                                <option :value="item" v-for="item in 12" :key="item">{{item}}</option>
                            </select>
                            <select v-model="endDateDay" class="form-control">
                                <option :value="item" v-for="item in days(endDateYear, endDateMonth)" :key="item">{{item}}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-space-around">
                <button class="btn btn-sm btn-danger" @click="cancelEdit">キャンセル</button>
                <button class="btn btn-sm btn-success" @click="save">変更を保存</button>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'semester',
        event: 'change'
    },
    props: ['semester', 'types'],
    data(){
        return{
            endYear: 2050,
            startYear: 2010
        }
    },
    computed: {
        year: {
            get(){
                return this.semester.year;
            },
            set(year){
                this.updateValue({year});
            }
        },
        type: {
            get(){
                return this.semester.type;
            },
            set(type){
                this.updateValue({type});
            }
        },
        startDateYear: {
            get(){
                return this.semester.startDate.year;
            },
            set(year){
                let startDate = this.updateStartDate({year});
                this.updateValue({startDate})
            }
        },
        startDateMonth: {
            get(){
                return this.semester.startDate.month;
            },
            set(month){
                let startDate = this.updateStartDate({month});
                this.updateValue({startDate})
            }
        },
        startDateDay: {
            get(){
                return this.semester.startDate.day;
            },
            set(day){
                let startDate = this.updateStartDate({day});
                this.updateValue({startDate})
            }
        },
        endDateYear: {
            get(){
                return this.semester.endDate.year;
            },
            set(year){
                let endDate = this.updateEndDate({year});
                this.updateValue({endDate})
            }
        },
        endDateMonth: {
            get(){
                return this.semester.endDate.month;
            },
            set(month){
                let endDate = this.updateEndDate({month});
                this.updateValue({endDate})
            }
        },
        endDateDay: {
            get(){
                return this.semester.endDate.day;
            },
            set(day){
                let endDate = this.updateEndDate({day});
                this.updateValue({endDate})
            }
        },
        years(){
            var yearNum = this.endYear - this.startYear;
            var yearsArray = [];
            for(var i = 0; i < yearNum; i++){
                var item = this.startYear + i;
                yearsArray.push(this.startYear + i);
            }
            return yearsArray;
        },
    },
    methods: {
        cancelEdit(){
            this.$emit('cancel');
        },
        days(year, month){
            let date = new Date(year, month, 0);
            return date.getDate();
        },
        save(){
            if(this.semester.id){
                this.$emit('edit');
            }else{
                this.$emit('add');
            }
        },
        updateValue(value){
            this.$emit('change', {...this.semester, ...value})
        },
        updateStartDate(value){
            return {
                ...this.semester.startDate,
                ...value
            }
        },
        updateEndDate(value){
            return {
                ...this.semester.endDate,
                ...value
            }
        }
    }
}
</script>