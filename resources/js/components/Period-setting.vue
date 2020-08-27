<template>
	<div>
		<setting-head
			name="時限数と授業時間"
			link="setting"
			:option-button-visibility="!isEditing"
			@edit="toggleEditing"
		/>
		<div class="setting-body">
			<div class="setting-edit-block">
				<div class="setting-edit-block-inner">
					<template v-if="isEditing">
						<button class="btn btn-sm btn-danger" @click="toggleEditing">取消</button>
						<button class="btn btn-sm btn-success" @click="changePeriod">保存</button>
					</template>
				</div>
			</div>
			<ul class="setting-list-container">
				<template v-for="(period, index) in periods">
					<period-setting-item class="setting-list" :key="index" :period="period" :num="index" :is-editing="isEditing" v-model="periods[index]" />
				</template>
			</ul>
		</div>
	</div>
</template>

<script>
import PeriodSettingItem from './modules/Period-setting-item'
import SettingHead from './modules/Setting-head'

export default {
    components: {
        PeriodSettingItem,
        SettingHead
    },
    data(){
        return{
            isEditing: false,
            periods: [],
        }
    },
    computed: {
        auth(){
            return __auth();
        },
    },
    methods: {
			changePeriod(){
				var formData = {
					first_start_time: this.periods[0][0],
					first_end_time: this.periods[0][1],
					second_start_time: this.periods[1][0],
					second_end_time: this.periods[1][1],
					third_start_time: this.periods[2][0],
					third_end_time: this.periods[2][1],
					fourth_start_time: this.periods[3][0],
					fourth_end_time: this.periods[3][1],
					fifth_start_time: this.periods[4][0],
					fifth_end_time: this.periods[4][1],
					sixth_start_time: this.periods[5][0],
					sixth_end_time: this.periods[5][1]
				}
				axios.put(`/setting/period/${this.auth.id}`, formData)
				.then((res) => {
					this.isEditing = false
				})
				.catch((err) => {
					console.log(err)
				})
			},
			getSettingInfo(){
				axios.get(`/api/user/setting/${this.auth.id}`)
				.then(({data}) => {
						this.periods = data.periods;
				})
				.catch((err) => {
						console.log(err);
				})
			},
			toggleEditing(){
				this.isEditing = !this.isEditing
			}
    },
    mounted(){
        this.getSettingInfo();
    }
}
</script>