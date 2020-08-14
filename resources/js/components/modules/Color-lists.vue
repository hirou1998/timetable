<template>
    <div class="color-area">
        <div v-for="col in colorList" :key="col">
            <label 
                class="color-circle" :style="{backgroundColor: col}"
                :aria-checked="col === currentColor ? true : false">
                <input type="radio" v-model="currentColor" :value="col"
                class="color-circle-input"
                >
            </label>
        </div>
        <!-- <el-color-picker v-model="color1"></el-color-picker> -->
    </div>
</template>

<script>
export default {
    model: {
        prop: 'color',
        event: 'change'
    },
    props: ['color'],
    data(){
        return{
            colorList: ['#B6ABE4', '#B5179D', '#ABC9E3', '#F2EFAC', '#B8EDE1'],
        }
    },
    computed: {
        currentColor: {
            get(){
                return this.color.toUpperCase();
            },
            set(color){
                this.updateValue(color)
            }
        },
    },
    methods: {
        updateValue(value){
            this.$emit('change', value)
        }
    }
}
</script>