<template>
    <form>
        <div class="mb-1">
            <p class="setting-form-text">大学</p>
            <input type="text" class="form-control" v-model="university">
        </div>
        <div>
            <p class="setting-form-text">入学年</p>
            <select class="form-control" v-model="enterYear">
                <option :value="item" v-for="item in years" :key="item">{{item}}</option>
            </select>
        </div>
        <div>
            <p class="setting-form-text">卒業年（見込み）</p>
            <select class="form-control" v-model="graduationYear">
                <option :value="item" v-for="item in years" :key="item">{{item}}</option>
            </select>
        </div>
    </form>
</template>

<script>
export default {
    model: {
        prop: 'setting',
        event: 'change'
    },
    props: ['setting'],
    data(){
        return{
            endYear: 2050,
            startYear: 2010
        }
    },
    computed: {
        university: {
            get(){
                return this.setting.university
            },
            set(university){
                this.updateValue({university})
            }
        },
        enterYear: {
            get(){
                return this.setting.enter_year
            },
            set(enter_year){
                this.updateValue({enter_year})
            }
        },
        graduationYear: {
            get(){
                return this.setting.graduation_year
            },
            set(graduation_year){
                this.updateValue({graduation_year})
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
        updateValue(value){
            this.$emit('change', {...this.setting, ...value})
        }
    }
}
</script>