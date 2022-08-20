<template>
    <table :class="tableClass">
        <thead>
            <th>Model</th>
            <th>Indexed Fields</th>
        </thead>
        <tbody>
            <scout-model-table-row :v-for="model in models" :key="model.model" :scout-model="model"></scout-model-table-row>
        </tbody>
    </table>
</template>

<script>
import ScoutModelTableRow from "./ScoutModelTableRow";

export default {
  name: "ScoutModelTable",
  props: {
    bearerToken: {
      type: String
    },
    csrfToken: {
      type: String,
      required: true
    },
    getModelsUrl: {
      type: String,
      default: "/scout/models"
    },
    tableClass: {
      type: String,
      default: "table table-bordered"
    }
  },
  data() {
    return {
      models: []
    }
  },
  methods: {

    loadModels() {
        let headers = {
            Accept: 'application/json',
            'X-CSRF-TOKEN': this.csrfToken
        };
        if(this.bearerToken){
          headers.Authentication = this.bearerToken
        }
        fetch(this.getModelsUrl, {
          headers: headers
        })
            .then((resp) => {
              this.models = resp;
            })
            .catch((err)=>{
              console.error(err);
            });
    }
  },
  created(){
    this.loadModels();
  },
  components: {
    ScoutModelTableRow,
  },
}
</script>

<style scoped>

</style>