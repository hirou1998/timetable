<template>
    <section class="calendar-add-modal">
        <div class="calendar-add-modal-inner">
            <div class="calendar-add-modal-inner-main" :data-visibility="colorOptionsVisibility ? 'hidden' : 'visible'">
                <div class="calendar-add-modal-inner-head calendar-add-modal-inner-block">
                    <button class="calendar-add-modal-inner-cancel" @click="close">
                        <i class="fas fa-times i-normal"></i>
                    </button>
                    <div>
                        <input type="text" class="form-control calendar-add-modal-inner-form" v-model="title" placeholder="タイトルを入力">
                    </div>
                    <button class="btn btn-info btn-sm btn-white" @click="save">保存</button>
                </div>
                <div class="calendar-add-modal-inner-block">
                    <div class="calendar-add-modal-inner-icon">
                        <img src="/images/period-icon.png" alt="">
                    </div>
                    <div style="width: 50%;">
                        <p class="calendar-add-modal-inner-text" style="line-height: 1.4;">終日</p>
                        <el-date-picker
                        v-model="startDay"
                        type="date"
                        format="M月d日"
                        @change="changeEndDay">
                        </el-date-picker>
                        <el-date-picker
                        v-model="endDay"
                        type="date"
                        format="M月d日">
                        </el-date-picker>
                    </div>
                    <div style="width: 35%;">
                        <el-switch
                        v-model="allday"
                        style="marginBottom: .5rem;"
                        />
                        <template v-if="!allday">
                            <el-time-select
                            v-model="startTime"
                            @change="changeEndTime"
                            :picker-options="timePickerOptions"
                            >
                            </el-time-select>
                            <el-time-select
                            v-model="endTime"
                            :picker-options="timePickerOptions"
                            >
                            </el-time-select>
                        </template>
                    </div>
                </div>
                <div class="calendar-add-modal-inner-block">
                    <div class="calendar-add-modal-inner-icon">
                        <img src="/images/location.png" alt="">
                    </div>
                    <div style="flex: 7;">
                        <input class="calendar-add-modal-inner-form" type="text" v-model="location" placeholder="場所を追加">
                    </div>
                </div>
                <div class="calendar-add-modal-inner-block">
                    <div class="calendar-add-modal-inner-icon" style="align-items: center;">
                        <span :style="{backgroundColor: color}" class="color-icon"></span>
                    </div>
                    <div style="flex: 7;">
                        色を指定する
                        <button><i class="fas fa-chevron-right i-normal" @click="toggleColorOptions"></i></button>
                    </div>
                </div>
            </div>
            <div class="calendar-add-modal-inner-color" :data-visibility="colorOptionsVisibility ? 'visible' : 'hidden'">
                <div class="calendar-add-modal-inner-block">
                    <div style="flex: 7;" class="color-title">
                        <button @click="toggleColorOptions" class="d-flex mr-3"><i class="fas fa-chevron-left i-normal"></i></button>
                        色を指定する
                    </div>
                </div>
                <color-lists
                    v-model="color"
                />
            </div>
        </div>
    </section>
</template>

<script>
import ColorLists from './Color-lists'

export default {
    components: {
        ColorLists,
    },
    model: {
        prop: 'eventForm',
        event: 'change'
    },
    props: ['eventForm'],
    data(){
        return{
            prevStartTime: '10:00',
            timePickerOptions: {
                start: '0:00',
                step: '00:15',
                end: '23:45'
            },
            colorOptionsVisibility: false,
            colorList: ['#B6ABE4', '#B5179D', '#ABC9E3', '#F2EFAC', '#B8EDE1']
        }
    },
    computed: {
        allday: {
            get(){
                return this.eventForm.allday
            },
            set(allday){
                this.updateValue({allday})
            }
        },
        color: {
            get(){
                return this.eventForm.color
            },
            set(color){
                this.updateValue({color})
            }
        },
        endDay: {
            get(){
                return this.eventForm.endDay
            },
            set(endDay){
                this.updateValue({endDay})
            }
        },
        endTime: {
            get(){
                return this.eventForm.endTime
            },
            set(endTime){
                this.updateValue({endTime})
            }
        },
        location: {
            get(){
                return this.eventForm.location
            },
            set(location){
                this.updateValue({location})
            }
        },
        startDay: {
            get(){
                return this.eventForm.startDay
            },
            set(startDay){
                this.updateValue({startDay})
            }
        },
        startTime: {
            get(){
                return this.eventForm.startTime
            },
            set(startTime){
                this.updateValue({startTime})
            }
        },
        title: {
            get(){
                return this.eventForm.title
            },
            set(title){
                this.updateValue({title})
            }
        }
    },
    methods: {
        close(){
            this.$emit('close');
        },
        changeEndTime: function(newValue){
            var prev = this.prevStartTime.split(':');
            var prevToSecond = this.toSeconds(prev[0], prev[1]);
            var current = newValue.split(':');
            var currentToSecond = this.toSeconds(current[0], current[1]);
            var end = this.endTime.split(':');
            var endToSecond = this.toSeconds(end[0], end[1]);

            var transition = currentToSecond - prevToSecond;
            endToSecond = endToSecond + transition;
            var result = this.toHourAndMinute(endToSecond);
            this.endTime = result.hour + ':' + result.minute;
            this.prevStartTime = newValue;
        },
        changeEndDay: function(newValue){
            var start = new Date(newValue);
            var end = new Date(this.endDay);
            if(start.getTime() > end.getTime()){
                this.endDay = newValue;
            }
        },
        save(){
            this.$emit('save', this.eventForm);
        },
        toSeconds: function(hour, minute){
            return Number(hour) * 3600 + Number(minute) * 60;
        },
        toHourAndMinute: function(second){
            var hour = Math.floor(Math.abs(second) / 3600);
            var minute = Math.floor(Math.abs(second) % 3600 / 60);
            String(hour).length === 1 ? hour = '0' + hour : hour = hour;
            minute === 0 ? minute = '00' : minute = minute;
            return {
                hour: hour,
                minute, minute
            }
        },
        toggleColorOptions: function(){
            this.colorOptionsVisibility = !this.colorOptionsVisibility;
        },
        updateValue: function(value){
            this.$emit('change', {...this.eventForm, ...value})
        }
    }
}
</script>