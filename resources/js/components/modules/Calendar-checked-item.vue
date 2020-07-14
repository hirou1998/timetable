<template>
    <li 
        :data-course="assignment.course.name"
        class="calendar-checked-item"
    >
        <h3 class="calendar-checked-item-title">{{assignment.body}}</h3>
        <p class="calendar-checked-item-memo">・{{assignment.memo}}</p>
    </li>
</template>

<script>
export default {
    props: ['assignment'],
}
</script>