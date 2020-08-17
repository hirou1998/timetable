<template>
	<li>
		<div class="setting-list-icon">
			{{num + 1}}限
		</div>
		<p class="setting-list-name" v-if="!isEditing">
			<template v-for="(item, index) in period">
				{{item}}
				<template v-if="index === 0">~</template>
			</template>
		</p>
		<div class="form-group setting-list-name setting-end-form" v-else>
			<select class="form-control" data-live-search="true" v-model="startHour">
				<option v-for="num in hourNum" :value="num - 1" :key="num - 1">{{num - 1}}</option>
			</select>
			:
			<select class="form-control" data-live-search="true" v-model="startMinute">
				<option v-for="num in minuteNum" :value="num - 1" :key="num - 1">{{num - 1}}</option>
			</select>
			~
			<select class="form-control" data-live-search="true" v-model="endHour">
				<option v-for="num in hourNum" :value="num - 1" :key="num - 1">{{num - 1}}</option>
			</select>
			:
			<select class="form-control" data-live-search="true" v-model="endMinute">
				<option v-for="num in minuteNum" :value="num - 1" :key="num - 1">{{num - 1}}</option>
			</select>
		</div>
	</li>
</template>

<script>
export default {
	model: {
		prop: 'period',
    event: 'change'
	},
	props: ['period', 'num', 'isEditing'],
	data(){
		return{
			hourNum: 24,
			minuteNum: 60
		}
	},
	computed: {
		startPeriodSplit(){
			return this.period[0].split(':');
		},
		endPeriodSplit(){
			return this.period[1].split(':');
		},
		startHour: {
			get(){
				return Number(this.startPeriodSplit[0]);
			},
			set(hour){
				var minute = String(this.startMinute).length === 1 ? 0 + String(this.startMinute) : String(this.startMinute);
				var start = hour + ':' + minute;
				this.$emit('change', [start, this.period[1]])
			}
		},
		startMinute: {
			get(){
				return Number(this.startPeriodSplit[1]);
			},
			set(minute){
				var newMinute = String(minute).length === 1 ? 0 + String(minute) : String(minute);
				var start = this.startHour + ':' + newMinute;
				this.$emit('change', [start, this.period[1]])
			}
		},
		endHour: {
			get(){
				return Number(this.endPeriodSplit[0]);
			},
			set(hour){
				var minute = String(this.endMinute).length === 1 ? 0 + String(this.endMinute) : String(this.endMinute);
				var start = hour + ':' + minute;
				this.$emit('change', [this.period[0], start])
			}
		},
		endMinute: {
			get(){
				return Number(this.endPeriodSplit[1]);
			},
			set(minute){
				var newMinute = String(minute).length === 1 ? 0 + String(minute) : String(minute);
				var start = this.endHour + ':' + newMinute;
				this.$emit('change', [this.period[0], start])
			}
		}
	},
}
</script>