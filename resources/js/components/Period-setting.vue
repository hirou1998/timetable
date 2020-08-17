<template>
	<div>
		<setting-head
			name="時限数と授業時間"
			link="setting"
		/>
		<div class="setting-edit-block">
			<div class="setting-edit-block-inner">
				<template v-if="!isEditing">
					<button class="btn btn-sm btn-info btn-white" @click="toggleEditing">編集</button>
				</template>
				<template v-else>
					<button class="btn btn-sm btn-danger" @click="toggleEditing">取消</button>
					<button class="btn btn-sm btn-success">保存</button>
				</template>
			</div>
		</div>
		<div class="setting-body">
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