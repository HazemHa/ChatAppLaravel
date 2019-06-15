<template>
  <v-container fluid fill-height>
        <v-layout align-center justify-center>
                <v-form>
                  <v-text-field prepend-icon="person" name="login" label="Login" v-model="email" type="text"></v-text-field>
                  <v-text-field id="password" prepend-icon="lock" name="password" v-model="password" label="Password" type="password"></v-text-field>
                </v-form>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="login">Login</v-btn>
              </v-card-actions>
        </v-layout>
  </v-container>

</template>

<script>
  export default {
    data: () => ({
      drawer: null,
      email:'',
      password:'',
    }),
    created() {      
     
    },

    props: {
      source: String
    },
    methods:{
      login(){
        this.$store.dispatch('login',{email:this.email,password:this.password})
        .then((res)=> {
          this.$router.push('/')})
        .catch((err)=>  this.$toaster.error('Something happened error, try again or contact the support'));
      }
    }
  }
</script>
