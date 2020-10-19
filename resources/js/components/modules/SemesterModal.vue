<template>
    <div class="modal-base">
        <section class="semester-edit-window">
            <div class="semester-edit-inner">
                <form>
                    <div class="semester-edit-block">
                        <select v-model="year" class="form-control">
                            <option :value="item" v-for="item in years" :key="item">{{item}}</option>
                        </select>
                    </div>
                    <div class="semester-edit-block">
                        <select v-model="type" class="form-control">
                            <option :value="item" v-for="item in types" :key="item">{{item}}</option>
                        </select>
                    </div>
                    <div class="semester-edit-block d-flex">
                        <select v-model="startDateYear" class="form-control">
                            <option :value="item" v-for="item in years" :key="item">{{item}}</option>
                        </select>
                        <select v-model="startDateMonth" class="form-control">
                            <option :value="item" v-for="item in 12" :key="item">{{item}}</option>
                        </select>
                        <select v-model="startDateDay" class="form-control">
                            <option :value="item" v-for="item in 31" :key="item">{{item}}</option>
                        </select>
                    </div>
                </form>
            </div>
            {{startDays()}}
            <div class="d-flex justify-content-space-around">
                <button class="btn btn-sm btn-danger" @click="cancelEdit">キャンセル</button>
                <button class="btn btn-sm btn-success">変更を保存</button>
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
                this.startDays();
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
        years(){
            var yearNum = this.endYear - this.startYear;
            var yearsArray = [];
            for(var i = 0; i < yearNum; i++){
                var item = this.startYear + i;
                yearsArray.push(this.startYear + i);
            }
            return yearsArray;
        },
        // startMonths(){
        //     let date = new Date(2020, this.startDateMonth, 0);
        //     return date;
        // }
    },
    methods: {
        cancelEdit(){
            this.$emit('cancel');
        },
        startDays(){
            let date = new Date(this.year, this.startDateMonth, 0);
            return this.startDateMonth;
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