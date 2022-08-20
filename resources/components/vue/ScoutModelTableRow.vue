<template>
    <tr>
        <td>{{scoutModel.model}}</td>
        <td>{{scoutModel.indexed_fields}}</td>
    </tr>
</template>

<script>
export default {
  name: "ScoutModelTableRow",
  props: {
    scoutModel: {
      type: Object,
      required: true,
      validator(value){
        return value.hasOwnProperty('model')
            && !value.model.isEmpty()
            && value.hasOwnProperty('indexed_fields')
            && !value.indexed_fields.isEmpty();
      }
    },
  },
}
</script>

<style scoped>

</style>